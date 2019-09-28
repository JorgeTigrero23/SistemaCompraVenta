<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'id',
        'contact',
        'contact_phone',
    ];

    public $timestamps = false;

    /* Relationship */

    public function people()
    {
        return $this->belongsTo('App\Models\Peope');
    }
}
