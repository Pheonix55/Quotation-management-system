<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationTerm extends Model
{

    protected $fillable = ['quotation_id', 'term_id', 'custom_text'];
    protected $table = 'quotation_term';
    public function quotations()
    {
        return $this->belongsToMany(Quotations::class, 'quotation_term', 'term_id', 'quotation_id');
    }
}
