<?php

namespace App;
use App\AnswerType;
use App\Answer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use OwenIt\Auditing\Contracts\Auditable;
class Node extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  /**
  * Create a relation with answers
  * @return relation
  */
  public function answers(){
    return $this->hasMany('App\Answer');
  }
<<<<<<< HEAD
=======

  /*
  * get or store nodes
  * @params $nodes
  * @params $algorithm
  * @return void
  */
  public static function getOrStore($nodes,$algorithm){
    $typeToAccept='Question';
    foreach($nodes as $node){
      if(array_key_exists('type', $node) && $node['type']==$typeToAccept){
        $priority=isset($node['is_mandatory'])?$node['is_mandatory']:0;
        $reference=isset($node['reference'])?$node['reference']:0;
        $stage=isset($node['stage'])?$node['stage']:'';
        $formula=isset($node['formula'])?$node['formula']:'';
        $answerType = AnswerType::getOrCreate($node['value_format']);
        $nodeSaved=Node::firstOrCreate(
          [
            'medal_c_id' => $node['id']
          ],
          [
            'reference' => $reference,
            'label' => $node['label'][Config::get('medal.creator.language')],
            'type' => $node['type'],
            'category' => $node['category'],
            'priority' => $priority,
            'stage' => $stage,
            'description' => isset($node['description'][Config::get('medal.creator.language')])?$node['description'][Config::get('medal.creator.language')]:'',
            'formula' => $formula,
            'answer_type_id'=>$answerType->id,
            'algorithm_id'=>$algorithm->id,
            'is_identifiable'=>$node['is_identifiable']
          ]
        );
        Answer::getOrCreate($nodeSaved,$node);
      }
    }
  }
>>>>>>> origin/testing
}
