<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetails extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'price',
    ];

    /* Relationship */

    public function purchase()
    {
        return $this->belognsTo('App\Models\Purchases');
    }

    public function product()
    {
        return $this->belognsTo('App\Models\Products');
    }
}
