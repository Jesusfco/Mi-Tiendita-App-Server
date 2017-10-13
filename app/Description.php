<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    protected $fillable = [
        'id_sales', 'id_product', 'price', 'quantity', 'subtotal'
    ];

    public $timestamps = false;
}
