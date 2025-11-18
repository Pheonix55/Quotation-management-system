<?php

namespace App\Models;
use App\QuotationStatus;
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
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_quote_request')
            ->withTimestamps();
    }
}
