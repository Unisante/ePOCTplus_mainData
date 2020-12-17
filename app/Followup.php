<?php

namespace App;



use Symfony\Component\Routing\Exception\InvalidParameterException;

class Followup
{
  /** @var MedicalCase $medicalCase */
  protected $medicalCase;

  /** @var Patient $patient */
  protected $patient;

  function __construct(MedicalCase $medicalCase, Patient $patient)
  {
    $this->medicalCase = $medicalCase;
    $this->patient = $patient;
  }

  public function getMedicalCase() : MedicalCase
  {
    return $this->medicalCase;
  }

  public function getPatient() : Patient
  {
    return $this->patient;
  }

  public function isRedcapFlagForMedicalCase() : bool
  {
    return $this->medicalCase->isRedcapFlagged();
  }

  public function isRedcapFlagForPatient() : bool
  {
    return $this->patient->isRedcapFlagged();
  }

}
