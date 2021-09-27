<?php

namespace App\Services;

use Doctrine\Common\Cache\Psr6\InvalidArgument;
use \InvalidArgumentException;

abstract class ExportService
{
    protected $medical_cases;

    /**
     * Checks if the medical case list is valid.
     * @param medical_cases the list of medical cases
     * @throws InvalidArgumentException
     */
    protected static function checkMedicalCases($medical_cases)
    {
        if($medical_cases === null){
            throw new InvalidArgumentException("Medical cases should not be null.");
        }

        if(count($medical_cases) == 0){
            throw new InvalidArgumentException("Medical cases cannot be empty.");
        }

        foreach($medical_cases as $medical_case){
            if($medical_case === null){
                throw new InvalidArgumentException("Medical cases cannot contain null elements.");
            }
        }
    }

    /**
     * Exports the data.
     */
    abstract public function export();
}