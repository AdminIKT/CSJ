<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;

class ChargeImport implements ToModel
{
    /**
     * @inheritDoc
     */
    public function model(array $row)
    {
        return []; 
    }
}
