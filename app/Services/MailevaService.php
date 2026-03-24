<?php

namespace App\Services;

use App\Actions\GetDocumentFromSending;
use App\Actions\GetSenderFromSending;
// use App\Actions\MakeCouFromCampaign;
use App\Contracts\PostLetter;
use App\DataTransferObjects\PostLetter\AddressLinesData;
use App\DataTransferObjects\PostLetter\SendingData;
use App\DataTransferObjects\PostLetter\ContentData;
use App\DataTransferObjects\PostLetter\DocumentData as PostLetterDocumentData;
use App\DataTransferObjects\PostLetter\DocumentOptionPaperData;
use App\DataTransferObjects\PostLetter\FoldData as PostLetterFoldData;
use App\DataTransferObjects\PostLetter\FoldOptionPaperData;
use App\DataTransferObjects\PostLetter\NotificationData;
use App\DataTransferObjects\PostLetter\OptionData;
use App\DataTransferObjects\PostLetter\PaperAddress;
use App\DataTransferObjects\PostLetter\PaperOptionData;
use App\DataTransferObjects\PostLetter\ProtocolData;
use App\DataTransferObjects\PostLetter\ProtocolTypeData;
use App\DataTransferObjects\PostLetter\RecipientData;
use App\DataTransferObjects\PostLetter\RequestData;
use App\DataTransferObjects\PostLetter\SenderData;
use App\DataTransferObjects\PostLetter\UserData;
use App\Enums\AddressType;
use App\Enums\DocumentType;
use App\Enums\MediaType;
use App\Enums\NotificationFormat;
use App\Enums\NotificationType;
use App\Enums\PostageClassType;
use App\Enums\PostageType;
use App\Helpers\Accounting;
use App\Models\Sending;
use App\Settings\MailevaSettings;
use DOMDocument;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use phpseclib3\Net\SFTP;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\LaravelData\DataCollection;
use ZipArchive;
use App\Services\MailevaAuthService;
use Illuminate\Support\Facades\Http;
use setasign\Fpdi\Fpdi;

class MailevaService implements PostLetter
{
    /**
     * @param string $login
     * @param string $password
     * @param string $ftpLogin
     */
    public function __construct(
        private readonly string $login,
        private readonly string $password,
        private MailevaAuthService $auth,
        protected MailevaSettings $mailevaSettings
    ) {
        $this->baseUrl = config('maileva.base_url').'/registered_mail/v4';
        $this->token = $auth->getAccessToken();
    }

    /*
     * Creates a new sending in database
     */
    public function newSending(): SendingData
    {
        $mailevaSettings = $this->mailevaSettings;
        ++$mailevaSettings->sending_number;

        $sending = new SendingData(
            user: new UserData(
                auth_type: 'PLAINTEXT',
                login: $this->login,
                password: $this->password,
            ),
            requests: RequestData::collection([]),
            version: $mailevaSettings->version,
            name: $mailevaSettings->name,
            track_id: Accounting::makeNumber($mailevaSettings->sending_prefix, $mailevaSettings->sending_number),
        );

        $mailevaSettings->save();

        return $sending;
    }

    /**
     * @param String $trackId
     * @param PostageType $postageType
     * @param DataCollection $recipients
     * @param DataCollection $senders
     * @param DataCollection $documents
     * @param DataCollection $options
     * @return RequestData
     */
    public function newRequest(
        String $trackId,
        PostageType $postageType,
        DataCollection $recipients,
        DataCollection $senders,
        DataCollection $documents,
        DataCollection $options,
    ): RequestData
    {
        $recipients = RecipientData::collection($recipients->map(static function ($address) {
            $id = 'ID_' . Str::random(29);

            return new RecipientData(
                paper_address: new PaperAddress(
                    address_lines: new AddressLinesData(
                        address_line_1: ($address->type === AddressType::PROFESSIONAL) ? $address->compagny : $address->first_name . ' ' . $address->last_name,
                        address_line_2: $address->address_line_2,
                        address_line_3: $address->address_line_3,
                        address_line_4: $address->address_line_4,
                        address_line_5: $address->address_line_5,
                        address_line_6: $address->postal_code . ' ' . $address->city,
                    ),
                    country: $address->country,
                    country_code: $address->country_code,
                ),
                category: $address->type->value,
                id: $id,
                track_id: $id,
                partner_track_id: config('maileva.partner_track_id'),
            );
        }));

        $senders = SenderData::collection($senders->map(static function ($address) {
            $id = 'ID_' . Str::random(29);

            return new SenderData(
                paper_address: new PaperAddress(
                    address_lines:  new AddressLinesData(
                        address_line_1: ($address->type === AddressType::PROFESSIONAL) ? $address->compagny : $address->first_name . ' ' . $address->last_name,
                        address_line_2: $address->address_line_2,
                        address_line_3: $address->address_line_3,
                        address_line_4: $address->address_line_4,
                        address_line_5: $address->address_line_5,
                        address_line_6: $address->postal_code . ' ' . $address->city,
                    ),
                    country: $address->country,
                    country_code: $address->country_code,
                ),
                id: $id,
            );
        }));

        $options = collect($options)->pluck('name')->toArray();

        if ($postageType === PostageType::REGISTERED_LETTER) {
            if (in_array('receipt', $options, true)) {
                $postageClassType = PostageClassType::LRE_AR;
            } else {
                $postageClassType = PostageClassType::LRE;
            }
        } else if ($postageType === PostageType::GREEN_LETTER) {
            $postageClassType = PostageClassType::LETTRE_VERTE;
        } else {
            //$postageClassType = PostageClassType::LSM;
            $postageClassType = PostageClassType::LRE;
        }

        $has_letter = false;

        if($documents->first(
            fn ($document) => $document->type === DocumentType::TEMPLATE || $document->type === DocumentType::HANDWRITE
        ) && $documents->count() <= 1) {
            $has_letter = true;
        }

        $opts = OptionData::collection([]);
        $opts[] = new OptionData(
            request_option:  new PaperOptionData(
                fold_option: new FoldOptionPaperData(
                    document_option: new DocumentOptionPaperData(
                        print_duplex: in_array('recto_verso', $options, true),
                    ),
                    postage_class: $postageClassType->value,
                    fold_print_color: in_array('color_print', $options, true),
                    print_sender_address: $has_letter,
                    use_fly_leaf: ! $has_letter,
                )
            )
        );

        return new RequestData(
            recipients: $recipients,
            senders: $senders,
            documentData: PostLetterDocumentData::collection($documents->map(static function ($document) {
                $id = 'ID_' . Str::random(29);

                return new PostLetterDocumentData(
                    id: $id,
                    content: new ContentData(
                        uri: $document->path,
                    ),
                    shrink: false,
                    size: $document->size,
                );
            })),
            options: $opts,
            folds: PostLetterFoldData::collection($recipients->map(function ($recipient) use ($senders) {
                $id = 'ID_' . Str::random(29);

                return new PostLetterFoldData(
                    id: $id,
                    recipient_id: $recipient->id,
                    sender_id: $senders[0]->id,
                    track_id: $id,
                );
            })),
            notifications: NotificationData::collection([
                new NotificationData(
                    type: NotificationType::GENERAL->value,
                    format: NotificationFormat::XML->value,
                    protocols: ProtocolData::collection([
                        new ProtocolData(
                            protocol: new ProtocolTypeData(
                                attribute: 'xml',
                                value: '',
                            )
                        )
                    ]),
                ),
                new NotificationData(
                    type: NotificationType::LRE->value,
                    format: NotificationFormat::TXT->value,
                    protocols: ProtocolData::collection([
                        new ProtocolData(
                            protocol: new ProtocolTypeData(
                                attribute: 'Email',
                                value: 'lre@mail.stop-contrat.com',
                            )
                        )
                    ]),
                ),
                new NotificationData(
                    type: NotificationType::PND->value,
                    format: NotificationFormat::XML->value,
                    protocols: ProtocolData::collection([
                        new ProtocolData(
                            protocol: new ProtocolTypeData(
                                attribute: 'xml',
                                value: '',
                            )
                        )
                    ]),
                ),
            ]),
            media_type: MediaType::PAPER->value,
            track_id: $trackId,
            stamp_adjust: false,
        );
    }

    /**
     * @param Sending $sending
     * @return integer The Maileva sending number
     */
    private function createSending(Sending $sending): string
    {
        $sender = (new GetSenderFromSending())->handle($sending->data);
        $address = $sender->paper_address->address_lines;

        $data = [
            'name' => $sending->order->number,
            'custom_id' => 'sending_'.$sending->id,
            'custom_data' => time(),
            'color_printing' => true,
            'duplex_printing' => true,
            'optional_address_sheet' => false,
            'notification_email' => 'do_not_reply@maileva.com',
            'print_sender_address' => false,
            'sender_address_line_1' => $address->address_line_1,
            'sender_address_line_2' => $address->address_line_2,
            'sender_address_line_3' => $address->address_line_3,
            'sender_address_line_4' => $address->address_line_4,
            'sender_address_line_5' => $address->address_line_5,
            'sender_address_line_6' => $address->address_line_6,
            'sender_country_code' => 'FR',
            'archiving_duration' => 0,
            'envelope_windows_type' => 'SIMPLE',
            'postage_type' => 'FAST',
            'treat_undelivered_mail' => false,
            'notification_treat_undelivered_mail' => [
                'email@domain.com',
                'email_bis@domain.com',
            ],
        ];

        $response = Http::withToken($this->token)
            ->acceptJson()
            ->post($this->baseUrl . '/sendings', $data);

        if (!$response->successful()) {

            Log::channel('maileva')->error("Erreur de création de l'envoi", [
                'sending_id' => $sending->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException(
                "Erreur avec l'API Maileva : ({$response->status()}): ".$response->body()
            );
        }

        $mailevaSendingId = $response['id'];

        Log::channel('maileva')->info("    Création de l'envoi réussie", [
            'sending_id' => $sending->id,
            'maileva_id' => $mailevaSendingId
        ]);

        return $mailevaSendingId;
    }
}
