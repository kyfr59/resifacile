<?php

namespace App\Services;

// use App\Actions\GetAllDocumentsFromCampaign;
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
    ) {}

    /**
     * @return CampaignData
     */
    /*
    public function newCampaign(): CampaignData
    {
        $mailevaSettings = App::make(MailevaSettings::class);

        ++$mailevaSettings->sending_number;

        $campaign = new CampaignData(
            user: new UserData(
                auth_type: 'PLAINTEXT',
                login: $this->login,
                password: $this->password,
            ),
            requests: RequestData::collection([]),
            version: $mailevaSettings->version,
            partner_track_id: config('maileva.partner_track_id'),
            name: $mailevaSettings->name,
            track_id: Accounting::makeNumber($mailevaSettings->campaign_prefix, $mailevaSettings->sending_number),
        );

        $mailevaSettings->save();

        return $campaign;
    }
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
     * @param SendingData $sending
     * @return void
     */
    public function pushSending(SendingData $sending): void
    {
        $token = $this->auth->getAccessToken();
        dd("pushSending");

        /*
        $campaignName = $campaign->track_id;
        $path = 'campaigns/executed/' . $campaignName;
        $pathZip = storage_path('app/executed/' . $campaignName . '.zcou.tmp');
        Storage::makeDirectory($path);

        try {
            $documents = (new GetAllDocumentsFromCampaign())->handle($campaign);

            $translateDocuments = [];

            for ($i = 0; $i < count($documents); $i++) {
                $realPath = $documents[$i]->content->uri;
                $name = $campaignName . '.' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
                $translateDocuments[$realPath] = $name;
                $executedName = $path . DIRECTORY_SEPARATOR .$name.'.pdf';

                Storage::copy(
                    $realPath,
                    $executedName,
                );

                $fullPath = Storage::path($executedName);
                $token = $this->auth->getAccessToken();

                $id = (string) \Illuminate\Support\Str::uuid();
                $path = $executedName; // ton chemin relatif storage
                $fullPath = Storage::path($path);
                $size = Storage::size($path);
                $mimeType = Storage::mimeType($path);
                $pdf = new Fpdi();
                $pageCount = $pdf->setSourceFile($fullPath);
                $sheetsCount = (int) ceil($pageCount / 2);

                $documentPayload = [
                    'id' => (string) Str::uuid(),
                    'type' => $mimeType, // application/pdf
                    'pages_count' => $pageCount,
                    'sheets_count' => $sheetsCount,
                    'size' => $size,
                    'converted_size' => $size, // ou valeur retournée par API si conversion
                ];


                $url = 'https://api.sandbox.maileva.net/mail/v2/sendings/eb987f58-696d-47fd-bf16-a5fc5c4838f2/documents';

                $response = Http::withToken($token)
                    ->acceptJson()
                    ->attach(
                        'document',
                        fopen($fullPath, 'r'),
                        basename($fullPath)
                    )
                    ->attach(
                        'metadata',
                        json_encode([
                            'priority' => 3,
                            'name' => basename($fullPath),
                            'shrink' => true,
                        ]),
                        'metadata.json'
                    )
                    ->post($url);

                    dd($response->headers());

            if (!$response->successful()) {
                throw new \Exception(
                    'Erreur API Maileva: ' .
                    $response->status() . ' - ' .
                    $response->body()
                );
            }
            Log::info('Maileva campaign envoyée', [
                'track_id' => $campaignName,
                'response' => $response->json()
            ]);
        }

        } catch (Exception $e) {
            if(Storage::directories($path)) {
                Storage::deleteDirectory($path);
            }

            if(Storage::disk('local')->get('app/executed/' . $campaignName . '.zcou.tmp')) {
                Storage::disk('local')->delete('app/executed/' . $campaignName . '.zcou.tmp');
            }

            Log::error($e);
        }
        */
    }
}
