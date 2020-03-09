<?php

use Illuminate\Database\Seeder;
use App\Node;
class NodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nodes=[
            [   'type'=>'Question',
                'reference'=>346,
                'label'=>'Respiratory rate in percentile',
                'category'=>'physical_exam',
                'medal_c_id'=>346,
                'priority'=>true,
                'stage'=>'consultation',
                'description'=>'from the seeder',
                'formula'=>'formula seeder',
                'answer_type_id'=>1,
                'algorithm_id'=>1,
            ],
            [   'type'=>'Question',
                'reference'=>47,
                'label'=>'MMR: 2 doses completed',
                'category'=>'vaccine',
                'medal_c_id'=>47,
                'priority'=>false,
                'stage'=>'registration',
                'description'=>'from the seeder',
                'formula'=>'formula seeder',
                'answer_type_id'=>1,
                'algorithm_id'=>1,
            ],
            [   'type'=>'Question',
                'reference'=>48,
                'label'=>'weight for age',
                'category'=>'physical_exam',
                'medal_c_id'=>48,
                'priority'=>true,
                'stage'=>'registration',
                'description'=>'weight for age according to WHO reference tables',
                'formula'=>'formula seeder',
                'answer_type_id'=>1,
                'algorithm_id'=>1,
            ],
            [   'type'=>'Question',
                'reference'=>49,
                'label'=>'MUAC',
                'category'=>'physical_exam',
                'medal_c_id'=>49,
                'priority'=>true,
                'stage'=>'consultation',
                'description'=>'Mid upper arm circonference to be mesures only in children >/= 6 months of age',
                'formula'=>'formula seeder',
                'answer_type_id'=>1,
                'algorithm_id'=>1,
            ],
            [   'type'=>'Question',
                'reference'=>50,
                'label'=>'age',
                'category'=>'demographic',
                'medal_c_id'=>50,
                'priority'=>true,
                'stage'=>'registration',
                'description'=>'Mid upper arm circonference to be mesures only in children >/= 6 months of age',
                'formula'=>'formula seeder',
                'answer_type_id'=>1,
                'algorithm_id'=>1,
            ],

    ];

    foreach($nodes as $node)
    {
        Node::create($node);
    }


    }
}
