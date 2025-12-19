<?php

namespace App\Services;

use App\Actions\GetAllDocumentsFromCampaign;
use App\Actions\MakeCouFromCampaign;
use App\Contracts\PostLetter;
use App\DataTransferObjects\PostLetter\AddressLinesData;
use App\DataTransferObjects\PostLetter\CampaignData;
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
    ) {}

    /**
     * @return CampaignData
     */
    public function newCampaign(): CampaignData
    {
        $mailevaSettings = App::make(MailevaSettings::class);

        ++$mailevaSettings->campaign_number;

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
            track_id: Accounting::makeNumber($mailevaSettings->campaign_prefix, $mailevaSettings->campaign_number),
        );

        $mailevaSettings->save();

        return $campaign;
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
     * @param CampaignData $campaign
     * @return void
     */
    public function pushCampaign(CampaignData $campaign): void
    {
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

                Storage::copy(
                    $realPath,
                    $path . DIRECTORY_SEPARATOR .$name,
                );
            }

            $xml = $this->xml($campaign->toArray(), $translateDocuments);
            $dom = new DOMDocument('1.0', 'UTF-8');

            $dom->loadXML($xml);

            $dom->schemaValidate(base_path('XSD/MailevaPJS.xsd'));

            Storage::put($path . DIRECTORY_SEPARATOR . $campaignName . '.pjs', $dom->saveXML());

            $zip = new ZipArchive();
            $zip->open($pathZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            foreach (Storage::files($path) as $file) {
                $zip->addFromString(last(explode('/', $file)), Storage::get($file));
            }

            $zip->close();

            $sftp = new SFTP(
                config('filesystems.disks.sftp.host'),
                config('filesystems.disks.sftp.port'),
                10
            );

            $sftp->login(config('filesystems.disks.sftp.username'), config('filesystems.disks.sftp.password'));

            $sftp->put($campaignName . '.zcou.tmp', Storage::disk('local')->get('executed/' . $campaignName . '.zcou.tmp'));
            $sftp->rename($campaignName . '.zcou.tmp', $campaignName . '.zcou');

            $sftp->disconnect();
        } catch (Exception $e) {
            if(Storage::directories($path)) {
                Storage::deleteDirectory($path);
            }

            if(Storage::disk('local')->get('app/executed/' . $campaignName . '.zcou.tmp')) {
                Storage::disk('local')->delete('app/executed/' . $campaignName . '.zcou.tmp');
            }

            Log::error($e);
        }
    }

    /**
     * @param array $campaign
     * @param array $translateDocuments
     * @return string
     */
    private function xml(array $campaign, array $translateDocuments): string
    {
        return ArrayToXml::convert(
            array: [
                '__custom:pjs\\:User' => [
                    '_attributes' => [
                        'AuthType' => $campaign['user']['auth_type'],
                    ],
                    '__custom:pjs\\:Login' => $campaign['user']['login'],
                    '__custom:pjs\\:Password' => $campaign['user']['password'],
                ],
                '__custom:pjs\\:Requests' => [
                    '__custom:pjs\\:Request' => Arr::map($campaign['requests'], static fn ($request) => [
                        '_attributes' => [
                            'TrackId' => $request['track_id'],
                            'MediaType' => $request['media_type'],
                        ],
                        '__custom:pjs\\:Recipients' => [
                            '__custom:pjs\\:Internal' => [
                                '__custom:pjs\\:Recipient' => Arr::map($request['recipients'], static fn ($recipient) => [
                                    '_attributes' => [
                                        'Id' => $recipient['id'],
                                    ],
                                    '__custom:com\\:PaperAddress' => [
                                        '__custom:com\\:AddressLines' => Arr::whereNotNull([
                                            '__custom:com\\:AddressLine1' => $recipient['paper_address']['address_lines']['address_line_1'],
                                            '__custom:com\\:AddressLine2' => $recipient['paper_address']['address_lines']['address_line_2'],
                                            '__custom:com\\:AddressLine3' => $recipient['paper_address']['address_lines']['address_line_3'],
                                            '__custom:com\\:AddressLine4' => $recipient['paper_address']['address_lines']['address_line_4'],
                                            '__custom:com\\:AddressLine5' => $recipient['paper_address']['address_lines']['address_line_5'],
                                            '__custom:com\\:AddressLine6' => $recipient['paper_address']['address_lines']['address_line_6'],
                                        ]),
                                        '__custom:com\\:Country' => $recipient['paper_address']['country'],
                                        '__custom:com\\:CountryCode' => $recipient['paper_address']['country_code']
                                    ],
                                ]),
                            ]
                        ],
                        '__custom:pjs\\:Senders' => [
                            '__custom:pjs\\:Sender' => Arr::map($request['senders'], static fn ($sender) => [
                                '_attributes' => [
                                    'Id' => $sender['id'],
                                ],
                                '__custom:com\\:PaperAddress' => [
                                    '__custom:com\\:AddressLines' => Arr::whereNotNull([
                                        '__custom:com\\:AddressLine1' => $sender['paper_address']['address_lines']['address_line_1'],
                                        '__custom:com\\:AddressLine2' => $sender['paper_address']['address_lines']['address_line_2'],
                                        '__custom:com\\:AddressLine3' => $sender['paper_address']['address_lines']['address_line_3'],
                                        '__custom:com\\:AddressLine4' => $sender['paper_address']['address_lines']['address_line_4'],
                                        '__custom:com\\:AddressLine5' => $sender['paper_address']['address_lines']['address_line_5'],
                                        '__custom:com\\:AddressLine6' => $sender['paper_address']['address_lines']['address_line_6'],
                                    ]),
                                    '__custom:com\\:Country' => $sender['paper_address']['country'],
                                    '__custom:com\\:CountryCode' => $sender['paper_address']['country_code']
                                ],
                            ]),
                        ],
                        '__custom:pjs\\:DocumentData' => [
                            '__custom:pjs\\:Documents' => [
                                '__custom:pjs\\:Document' => Arr::map($request['documentData'], static fn ($document) => [
                                    '_attributes' => [
                                        'Id' => substr($document['id'], 0, 15),
                                    ],
                                    '__custom:com\\:Shrink' => (int)$document['shrink'],
                                    '__custom:com\\:Content' => [
                                        '__custom:com\\:Uri' => $translateDocuments[$document['content']['uri']]
                                    ]
                                ]),
                            ],
                        ],
                        '__custom:pjs\\:Options' => [
                            '__custom:pjs\\:RequestOption' => Arr::map($request['options'], static fn ($option) => [
                                '__custom:spec\\:PaperOption' => [
                                    '__custom:spec\\:FoldOption' => [
                                        '__custom:spec\\:DocumentOption' => [
                                            '__custom:spec\\:PrintDuplex' => (int)$option['request_option']['fold_option']['document_option']['print_duplex'],
                                        ],
                                        '__custom:spec\\:PostageClass' => $option['request_option']['fold_option']['postage_class'],
                                        '__custom:spec\\:FoldPrintColor' => (int)$option['request_option']['fold_option']['fold_print_color'],
                                        '__custom:spec\\:PrintSenderAddress' => (int)$option['request_option']['fold_option']['print_sender_address'],
                                        '__custom:spec\\:UseFlyLeaf' => (int)$option['request_option']['fold_option']['use_fly_leaf'],
                                    ],
                                ],
                            ]),
                        ],
                        '__custom:pjs\\:Folds' => [
                            '__custom:pjs\\:Fold' => Arr::map($request['folds'], static fn ($fold) => [
                                '_attributes' => [
                                    'Id' => $fold['id'],
                                    'TrackId' => $fold['track_id']
                                ],
                                '__custom:pjs\\:RecipientId' => $fold['recipient_id'],
                                '__custom:pjs\\:SenderId' => $fold['sender_id'],
                            ]),
                        ],
                        '__custom:pjs\\:Notifications' =>  [
                            '__custom:pjs\\:Notification' => Arr::map($request['notifications'], static fn ($notification) => [
                                '_attributes' => [
                                    'Type' => $notification['type'],
                                ],
                                '__custom:spec\\:Format' => $notification['format'],
                                '__custom:spec\\:Protocols' => Arr::map($notification['protocols'], static function ($protocol) use ($notification) {
                                    if($notification['format'] === NotificationFormat::XML->value) {
                                        return [
                                            '__custom:spec\\:Protocol' => [
                                                '__custom:spec\\:Ftp' => []
                                            ]
                                        ];
                                    }
                                    return [
                                        '__custom:spec\\:Protocol' => [
                                            '__custom:spec\\:Email' => $protocol['protocol']['value']
                                        ]
                                    ];
                                })
                            ]),
                        ]
                    ]),
                ]
            ],
            rootElement: [
                'rootElementName' => 'pjs:Campaign',
                '_attributes' => [
                    'xmlns:com' => 'http://www.maileva.fr/CommonSchema',
                    'xmlns:pjs' => 'http://www.maileva.fr/MailevaPJSSchema',
                    'xmlns:spec' => 'http://www.maileva.fr/MailevaSpecificSchema',
                    'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                    'Version' => $campaign['version'],
                    'TrackId' => $campaign['track_id'],
                    'Application' => config('app.name'),
                ],
            ],
            domProperties: ['formatOutput' => true]
        );
    }
}
