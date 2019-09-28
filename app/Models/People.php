<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class People extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'document_type',
        'document_number',
        'address',
        'phone',
        'email',
    ];

    protected $dates = ['deleted_at'];

    /* Relationship */

    public function provider()
    {
        return $this->hasOne('App\Models\Provider');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function sale()
    {
        return $this->hasMany('App\Models\Sale');
    }
}
