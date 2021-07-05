<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Version;
use App\Node;
use App\PatientConfig;
class Algorithm extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

}
