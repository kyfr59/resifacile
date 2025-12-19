<?php

namespace App\DataTransferObjects\PostLetter;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class RequestData extends Data
{
    /**
     * @param DataCollection $recipients
     * @param DataCollection $senders
     * @param DataCollection $documentData
     * @param DataCollection $options
     * @param DataCollection $folds
     * @param DataCollection $notifications
     * @param string $media_type
     * @param string|null $media_sub_type
     * @param string|null $track_id
     * @param bool|null $stamp_adjust
     */
    public function __construct(
        #[Required,DataCollectionOf(RecipientData::class)]
        public DataCollection $recipients,
        #[DataCollectionOf(SenderData::class)]
        public DataCollection $senders,
        #[DataCollectionOf(DocumentData::class)]
        public DataCollection $documentData,
        #[DataCollectionOf(OptionData::class)]
        public DataCollection $options,
        #[DataCollectionOf(FoldData::class)]
        public DataCollection $folds,
        #[DataCollectionOf(NotificationData::class)]
        public DataCollection $notifications,
        #[Required]
        public string $media_type,
        #[Nullable]
        public ?string $media_sub_type = null,
        #[Nullable]
        public ?string $track_id = null,
        #[Nullable,BooleanType]
        // Ajustement de l’affranchissement au besoin.
        // Si l'affranchissement est 'LETTRE_GRAND_COMPTE' à positionner à TRUE.
        // Si l'affranchissement est 'ECOPLI_GRAND_COMPTE' à positionner à TRUE.
        public ?bool $stamp_adjust = null,
    ){
    }
}
