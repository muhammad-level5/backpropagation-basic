<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Create extends Model
{

    protected $table = 'data_train';

    protected $fillable = [
        'date','open', 'high',
        'low', 'close', 'volume',
        'target',
    ];

    protected $hidden = [

    ];
}
