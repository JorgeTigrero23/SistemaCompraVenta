<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'provider_id',
        'user_id',
        'voucher_type',
        'voucher_serie',
        'voucher_number',
        'date',
        'tax',
        'total',
        'status',
    ];

    /* Relationcship */

    public function provider()
    {
        return $this->belongsTo('App\Models\Providers');
    }

    public function user()
    {
        return $this->belongsTo('App\Users');
    }

    public function purchase_details()
    {
        return $this->hasMany('App\Models\PurchaseDetails');
    }

}
