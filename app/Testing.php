<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testing extends Model
{

    protected $table = 'data_test';

    protected $fillable = [
        'date', 'open', 'high',
        'low', 'close', 'volume',
        'target',
    ];

    protected $hidden = [

    ];
}
