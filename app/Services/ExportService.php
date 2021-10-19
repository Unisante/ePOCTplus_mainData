<?php

namespace App\Services;

use \InvalidArgumentException;

abstract class ExportService
{
    protected $medical_cases;

    /**
     * Checks if the medical case list is valid.
     * @param Collection medical_cases, the list of medical cases
     * @throws InvalidArgumentException
     */
    protected static function checkMedicalCases($medical_cases)
    {
        if ($medical_cases === null) {
            throw new InvalidArgumentException("Medical cases should not be null.");
        }

        foreach ($medical_cases as $medical_case) {
            if ($medical_case === null) {
                throw new InvalidArgumentException("Medical cases cannot contain null elements.");
            }
        }
    }

    /**
     * Constructor
     * @param Collection medical_cases, medical cases to export
     */
    public function __construct($medical_cases)
    {
        self::checkMedicalCases($medical_cases);

        $this->medical_cases = $medical_cases;
    }

    /**
     * Exports the data.
     */
    abstract public function export();
}
