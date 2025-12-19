<?php

namespace App\Models;

use App\Enums\InvoiceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'number',
        'path',
        'type',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'type' => InvoiceType::class,
    ];

    /**
     * @return BelongsTo
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
