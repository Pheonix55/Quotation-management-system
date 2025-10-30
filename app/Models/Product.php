<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'category_id', 'cost_price', 'sale_price', 'gst', 'bar_code', 'customer_id'];
    public function user()
    {
        return $this->belongsTo(Customer::class);
    }
}
