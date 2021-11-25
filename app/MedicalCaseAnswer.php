<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MedicalCaseAnswer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $guarded = [];

    /**
     * Get all audits of one medical case
     * @params $id
     * @return $all
     */
    public static function getAudit($id)
    {
        $medicalCaseAnswer = MedicalCaseAnswer::find($id);
        return $medicalCaseAnswer->audits;
    }

    public function medical_case()
    {
        return $this->belongsTo('App\MedicalCase');
    }
    public function answer()
    {
        return $this->belongsTo('App\Answer');
    }
    public function node()
    {
        return $this->belongsTo('App\Node');
    }
}
