<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',

    ];
    public function Products()
    {
        return $this->hasMany(Product::class);
    }
}
