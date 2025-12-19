<?php

namespace App\Models;

use App\DataTransferObjects\PostLetter\CampaignData;
use App\Enums\CampaignStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'status',
        'executed_at',
        'customer_id',
        'order_id',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'data' => CampaignData::class,
        'status' => CampaignStatus::class,
        'executed_at' => 'timestamp',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        //static::created(static function ($campaign) {
        //    $pendingExecutionCampaign = self::where('status', CampaignStatus::ACTIVE->value)->first();
        //    if($pendingExecutionCampaign) {
        //        $pendingExecutionCampaign->status = CampaignStatus::PENDING_EXECUTION;
        //        $pendingExecutionCampaign->save();
        //    }
        //    $campaign->status = CampaignStatus::ACTIVE;
        //    $campaign->save();
        //});
    }
}
