<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table;



    protected $fillable = [
        'name', 'code', 'price', 'reorder', 'stock', 'created_at'
    ];

    public $timestamps = false;
}
