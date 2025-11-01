<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'category_id', 'cost_price', 'sale_price', 'gst', 'bar_code', 'customer_id'];
    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function quotations()
    {
        return $this->belongsToMany(Quotation::class, 'quotation_product')
            ->withPivot('quantity', 'price', 'total')
            ->withTimestamps();
    }
    public function quoteRequests()
    {
        return $this->belongsToMany(QuoteRequest::class, 'product_quote_request');
    }
}
