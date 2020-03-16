<?php

use Illuminate\Database\Seeder;
use App\Patient;
use App\MedicalCase;
use App\Node;
use App\MedicalCaseAnswer;
use Faker\Generator as Faker;
class PatientSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run(Faker $faker)
    {
        $patientCreator=100;
        for ($k = 0 ; $k < $patientCreator; $k++)
        {
            $data['patient']=[
                'first_name'=>$faker->firstName,
                'last_name'=> $faker->lastName,
                'created_at'=>$faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            ];


            $patient=new Patient($data['patient']);

            $patient->save();

            $mc = $this->generate_medical_case($patient);

            $patient->medicalCases->add($mc);
        }

    }

    /**
    * Generates medical case.
    * @params $patient
    * @return void
    */
    function generate_medical_case($patient){
        $medical_case = new MedicalCase;
        $medical_case->version_id=1;
        $medical_case->patient_id = $patient->id;
        $medical_case->save();

        foreach(Node::all() as $question)
        {
            // we only need 80 percent of the answers
            if(rand(0,100) < 81){
                $answers = $question->answers->toArray();
                $random_answer_id = array_rand($answers);
                $answer= $answers[$random_answer_id];
                $medical_case_answers = new MedicalCaseAnswer([
                    "answer_id"=> $answer['id'],
                    "medical_case_id"=> $medical_case->id,
                    "node_id" => $answer['node_id'],
                    "value" => ""
                ]);
                $medical_case->medical_case_answers->add($medical_case_answers);
                $medical_case_answers->save();
            }
        }
        return $medical_case;
    }
}
