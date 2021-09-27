<?php

namespace App\Services;

use \InvalidArgumentException;
use Carbon\Carbon;
use App\Services\ExportCsv;

class ExportCsvSeparate extends ExportCsv
{

    /**
     * Constructor
     */
    public function __construct($medical_cases, $from_date, $to_date)
    {
        self::checkMedicalCases($medical_cases);
        self::checkDateInterval($from_date, $to_date);

        $this->medical_cases = $medical_cases;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function export()
    {

    }
}