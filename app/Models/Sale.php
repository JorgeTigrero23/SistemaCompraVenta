<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'client_id',
        'user_id',
        'voucher_type',
        'voucher_serie',
        'voucher_number',
        'date',
        'tax',
        'total',
        'status',
    ];

    /* Relationship */

    public function client()
    {
        return $this->belongsTo('App\Models\People');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function sale_details()
    {
        return $this->hasMany('App\Models\SaleDetails');
    }

}
