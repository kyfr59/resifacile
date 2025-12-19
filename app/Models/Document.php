<?php

namespace App\Models;

use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'readable_file_name',
        'file_name',
        'path',
        'size',
        'form_data',
        'type',
        'number_of_pages',
    ];

    protected $casts = [
        'form_data' => 'array',
        'type' => DocumentType::class,
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
