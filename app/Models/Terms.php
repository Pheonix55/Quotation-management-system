<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Terms extends Model
{
    protected $fillable = ['statements', 'customer_id'];
    public function quotations()
    {
        return $this->belongsToMany(Quotations::class, 'quotation_term', 'term_id', 'quotation_id');
    }
}
