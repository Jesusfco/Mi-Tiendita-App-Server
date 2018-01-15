<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    protected $fillable = [
        'sale_id', 'product_id', 'price', 'quantity', 'subtotal'
    ];

    public $timestamps = false;
}
