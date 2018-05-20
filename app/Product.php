<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table;



    protected $fillable = [
        'name', 'code', 'price', 'cost_price', 'reorder', 'stock', 'department', 'created_at', 'updated_at'
    ];

    public $timestamps = false;
}
