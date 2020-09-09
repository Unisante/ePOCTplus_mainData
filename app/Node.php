<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class Node extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  /**
  * Create a relation with answers
  * @return relation
  */
  public function getOrCreate($node_to_check){
    $node = Node::firstOrCreate(
      [
        'medal_c_id' => $node_to_check['id']
      ],
      [
        'reference' => $version_id,
        'label' => $version_id,
        'type' => $version_id,
        'category' => $version_id,
        'priority' => $version_id,
        'stage' => $version_id,
        'description' => $version_id,
        'formula' => $version_id,
        'answer_type_id'=>$id,
        'algorithm_id'=>$id,
      ]
    );
    return $node;
  }
  public function answers()
  {
    return $this->hasMany('App\Answer');
  }
}
