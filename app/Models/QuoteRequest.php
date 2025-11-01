<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'notes'
    ];
    protected $casts = [
        'status' => QuotationStatus::class,
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
