<?php

namespace App\Imports;

use App\Create;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CreateImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(!Create::where('date', '=', $row['date'])->exists()) {
            
            return new Create([
                'date'  =>  $row['date'],
                'open'  =>  $row['open'],
                'high'  =>  $row['high'],
                'low'   =>  $row['low'],
                'close' =>  $row['close'],
                'volume'=>  $row['volume'],
            ]);
        }
    }
}
