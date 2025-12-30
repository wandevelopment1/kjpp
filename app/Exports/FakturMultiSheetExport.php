<?php

// app/Exports/FakturMultiSheetExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FakturMultiSheetExport implements WithMultipleSheets
{
    protected $fakturs;

    public function __construct($fakturs)
    {
        $this->fakturs = $fakturs;
    }

    public function sheets(): array
    {
        return [
            new FakturExport($this->fakturs),
            new FakturDetailExport($this->fakturs),
        ];
    }
}
