<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'barcode',
        'name',
        'description',
        'price_sale',
        'stock',
        'stock_min',
    ];

    protected $dates = ['deleted_at'];

    /* Relationship */

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function sales()
    {
        return $this->hasMany('App\Models\Sale');
    }
    
}
