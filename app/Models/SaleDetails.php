<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetails extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'discount'
    ];

    /* Relationship */

    public function sale()
    {
        return $this->belongsTo('App\Models\Sale');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

}
