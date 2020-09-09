<?php

namespace App;
use App\AnswerType;
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
  public static function getOrCreate($node_to_check,$algorithm){
    $answerType = AnswerType::getOrCreate($node_to_check['value_format']);
    $node = Node::firstOrCreate(
      [
        'medal_c_id' => $node_to_check['id']
      ],
      [
        'reference' => $node_to_check['reference'],
        'label' => $node_to_check['label'],
        'type' => $node_to_check['type'],
        'category' => $node_to_check['category'],
        'priority' => $node_to_check['is_mandatory'],
        'stage' => $node_to_check['stage'],
        'description' => $node_to_check['description'],
        'formula' => $node_to_check['formula'],
        'answer_type_id'=>$answerType->id,
        'algorithm_id'=>$algorithm->id,
      ]
    );
    return $node;
  }
  public function answers()
  {
    return $this->hasMany('App\Answer');
  }
}
