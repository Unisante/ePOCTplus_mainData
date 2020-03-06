<?php

use Illuminate\Database\Seeder;
use App\Answer;
class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $answers=[
            ['node_id'=>4,'medal_c_id'=>501],
            ['node_id'=>4,'medal_c_id'=>502],
            ['node_id'=>4,'medal_c_id'=>503],
            ['node_id'=>5,'medal_c_id'=>91],
            ['node_id'=>5,'medal_c_id'=>92],
            ['node_id'=>6,'medal_c_id'=>369],
            ['node_id'=>6,'medal_c_id'=>370],
            ['node_id'=>6,'medal_c_id'=>371],
            ['node_id'=>7,'medal_c_id'=>372],
            ['node_id'=>7,'medal_c_id'=>372],
            ['node_id'=>8,'medal_c_id'=>97],
            ['node_id'=>8,'medal_c_id'=>98],
            ['node_id'=>8,'medal_c_id'=>99],
            ['node_id'=>8,'medal_c_id'=>100],
            ['node_id'=>8,'medal_c_id'=>101],
            ['node_id'=>8,'medal_c_id'=>102],
            ['node_id'=>8,'medal_c_id'=>334],

        ];

        foreach($answers as $answer)
        {
            Answer::create($answer);
        }
    }
}
