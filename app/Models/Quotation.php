<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'customer_id',
        'quotation_date',
        'quotation_time',
        'validity_date',
        'notes',
        'total',
        'is_completed',
        'product_ids',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function terms()
    {
        return $this->belongsToMany(Terms::class, 'quotation_term', 'quotation_id', 'term_id');
    }

    public function quotationTerms()
    {
        return $this->belongsToMany(QuotationTerm::class, 'quotation_term', 'quotation_id', 'term_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'quotation_product')
            ->withPivot('quantity', 'price', 'total')
            ->withTimestamps();
    }
}
