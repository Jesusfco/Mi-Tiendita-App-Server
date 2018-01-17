<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $table = 'services';
    protected $fillable = [
        'name', 'amount', 'promotion', 'description', 'months', 'products_limit',
        'users_limit', 'sales_months_limit'
    ];
}
