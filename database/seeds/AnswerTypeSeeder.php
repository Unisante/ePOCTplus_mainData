<?php

use Illuminate\Database\Seeder;
use App\AnswerType;
class AnswerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $answerType=[
            ['value'=>'Boolean'],
            ['value'=>'Array'],
            ['value'=>'Integer'],
            ['value'=>'Float'],
            ['value'=>'Date'],
            ['value'=>'String'],
        ];
        foreach($answerType as $answer){
            AnswerType::create($answer);
        }
    }
}
