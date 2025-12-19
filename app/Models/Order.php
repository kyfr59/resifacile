<?php

namespace App\Models;

use App\Actions\AddRequestToCampaign;
use App\Casts\OptionsConvertor;
use App\Casts\PriceConvertor;
use App\Contracts\PostLetter;
use App\DataTransferObjects\AddressData;
use App\DataTransferObjects\DocumentData;
use App\DataTransferObjects\OptionData;
use App\Enums\AddressType;
use App\Enums\DocumentType;
use App\Enums\OrderStatus;
use App\Enums\PostageType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\App;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class Order extends Model
{
    protected $fillable = [
        'number',
        'vat_rate',
        'amount',
        'postage',
        'options',
        'details',
        'documents_compliant',
        'ip_address',
        'status',
        'data',
        'with_subscription',
        'customer_id',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'amount' => PriceConvertor::class,
        'postage' => PostageType::class,
        'options' => OptionsConvertor::class,
        'details' => 'object',
        'status' => OrderStatus::class,
        'data' => 'object',
        'with_subscription' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function campaign(): HasOne
    {
        return $this->hasOne(Campaign::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    protected static function booted(): void
    {
        static::updated(function (Order $order) {
            if($order->status === OrderStatus::PAID) {
                (new AddRequestToCampaign())->handle(
                    requestData: (App::make(PostLetter::class))->newRequest(
                        trackId: 'CUS_' . $order->customer->id . '_' . now()->timestamp,
                        postageType: $order->postage,
                        recipients: AddressData::collection($order->details->recipients)->each(static function($address) {
                            if ($address->type === AddressType::PROFESSIONAL) {
                                $address->first_name = null;
                                $address->last_name = null;
                            } else {
                                $address->compagny = null;
                            }
                        }),
                        senders: AddressData::collection($order->details->senders)->each(static function($address) {
                            if ($address->type === AddressType::PROFESSIONAL) {
                                $address->first_name = null;
                                $address->last_name = null;
                            } else {
                                $address->compagny = null;
                            }
                        }),
                        documents: DocumentData::collection(collect($order->details->documents)->map(fn ($document) => new DocumentData(
                            file_name: $document->file_name,
                            readable_file_name: $document->readable_file_name,
                            path: $document->path,
                            size: $document->size,
                            type: match($document->type) {
                                DocumentType::HANDWRITE->value => DocumentType::HANDWRITE,
                                DocumentType::TEMPLATE->value => DocumentType::TEMPLATE,
                                DocumentType::PDF->value => DocumentType::PDF,
                                DocumentType::WORD->value => DocumentType::WORD,
                                DocumentType::JPG->value => DocumentType::JPG,
                                DocumentType::TXT->value => DocumentType::TXT,
                            },
                            number_of_pages: $document->number_of_pages,
                        ))),
                        options: OptionData::collection($order->options),
                    ),
                    order: $order,
                );
            }
        });
    }
}
