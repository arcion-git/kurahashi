<?php

namespace App\Imports;

use App\ShippingCalender;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ShippingCalenderImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ShippingCalender([
          'calender_id'=>$row['カレンダーID'],
          'date'=>$row['日付'],
        ]);
    }
}
