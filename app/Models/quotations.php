<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class quotations extends Model
{
    protected $fillable = [
        'customer_id',
        'quotation_date',
        'quotation_time',
        'validity_date',
        'notes',
        'total',
        'is_completed',
        'product_ids'
    ];
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function terms()
    {
        return $this->belongsToMany(Terms::class, 'quotation_term', 'quotation_id', 'term_id');
    }




    public function updateTotal()
    {
        $this->total = $this->items()->sum('subtotal');
        $this->save();
    }


}
