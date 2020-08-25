<?php

namespace App\Imports;

use App\Testing;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TestingImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(!Testing::where('date', '=', $row['date'])->exists()) {
            return new Testing([
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
