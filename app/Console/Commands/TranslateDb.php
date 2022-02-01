<?php

namespace App\Console\Commands;

use App\Answer;
use App\Diagnosis;
use App\Drug;
use App\MedicalCase;
use App\Node;
use App\VersionJson;
use Illuminate\Console\Command;

class TranslateDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:translate {language} {dry-run=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will update every labels in the choosen language';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->argument('dry-run') == 1) {
            $this->info('Dry Run');
        }
        $language = $this->argument('language');
        if (!$language) {
            $language = $this->choice(
                'Please choose a language?',
                ['en', 'fr']
            );
        }

        $this->info("Starting the translation");
        $this->info(MedicalCase::count() . " medical cases");

        MedicalCase::all()->each(function (MedicalCase $medical_case) use ($language) {
            $health_facility = $medical_case->patient->facility;
            if (!($health_facility)) {
                return false;
            }
            $versionJson = VersionJson::where('health_facility_id', $health_facility->first()->id)->first();
            $data = json_decode($versionJson->json, true);

            $medal_r_json = $data['medal_r_json'];

            $diagnoses = $medal_r_json['diagnoses'];
            $nodes = $medal_r_json['nodes'];
            $health_cares = $medal_r_json['health_cares'];
            $final_diagnoses = $medal_r_json['final_diagnoses'];

            $this->info("Processing health_cares");
            foreach ($health_cares as $health_care) {
                if (array_key_exists('id', $health_care)) {
                    $medal_c_id = $health_care['id'];
                    $label = $health_care['label']['en'];

                    if (array_key_exists($language, $health_care['label'])) {
                        $translated_label = $health_care['label'][$language];
                        if ($translated_label != "" && $label != "") {
                            if ($this->argument('dry-run') == 0) {
                                Drug::where('medal_c_id', $medal_c_id)->each(function (Drug $drug) use ($translated_label) {
                                    if ($drug->label != $translated_label) {
                                        $drug->update([
                                            'label' => $translated_label,
                                        ]);
                                        $this->info($drug->id . " updated");
                                    }
                                });
                            } else {
                                Drug::where('medal_c_id', $medal_c_id)->each(function (Drug $drug) use ($translated_label) {
                                    $this->info($drug->label);
                                    $this->warn($translated_label);
                                });
                            }
                        }
                    }
                    if (array_key_exists('description', $health_care)) {
                        if (array_key_exists($language, $health_care['description'])) {
                            $description = $health_care['description']['en'];
                            $translated_description = $health_care['description'][$language];
                            if ($translated_description != "" && $description != "") {
                                if ($this->argument('dry-run') == 0) {
                                    Drug::where('medal_c_id', $medal_c_id)->each(function (Drug $drug) use ($translated_description) {
                                        if ($drug->description != $translated_description) {
                                            $drug->update([
                                                'description' => $translated_description,
                                            ]);
                                            $this->info($drug->id . " updated");
                                        }
                                    });
                                } else {
                                    Drug::where('medal_c_id', $medal_c_id)->each(function (Drug $drug) use ($translated_description) {
                                        $this->info($drug->description);
                                        $this->warn($translated_description);
                                    });
                                }
                            }
                        }
                    }

                }
            }

            $this->info("Processing final_diagnoses");
            foreach ($final_diagnoses as $final_diagnose) {
                if (array_key_exists('id', $final_diagnose)) {
                    $medal_c_id = $final_diagnose['id'];
                    $label = $final_diagnose['label']['en'];

                    if (array_key_exists($language, $final_diagnose['label'])) {
                        $translated_label = $final_diagnose['label'][$language];
                        if ($translated_label != "" && $label != "") {
                            if ($this->argument('dry-run') == 0) {
                                Diagnosis::where('medal_c_id', $medal_c_id)->each(function (Diagnosis $diagnosis) use ($translated_label) {
                                    if ($diagnosis->label != $translated_label) {
                                        $diagnosis->update(['label' => $translated_label]);
                                        $this->info($diagnosis->id . " updated");
                                    }
                                });
                            } else {
                                Diagnosis::where('medal_c_id', $medal_c_id)->each(function (Diagnosis $diagnosis) use ($translated_label) {
                                    $this->info($diagnosis->label);
                                    $this->warn($translated_label);
                                });

                            }
                        }
                    }

                }
            }

            $this->info("Processing node");
            foreach ($nodes as $node) {
                if (array_key_exists('id', $node)) {
                    $medal_c_id = $node['id'];
                    $label = $node['label']['en'];

                    if (array_key_exists($language, $node['label'])) {
                        $translated_label = $node['label'][$language];
                        if ($translated_label != "" && $label != "") {
                            if ($this->argument('dry-run') == 0) {
                                Node::where('medal_c_id', $medal_c_id)->each(function (Node $node) use ($translated_label) {
                                    if ($node->label != $translated_label) {
                                        $node->update(['label' => $translated_label]);
                                        $this->info($node->id . " updated");
                                    }
                                });
                            } else {
                                Node::where('medal_c_id', $medal_c_id)->each(function (Node $node) use ($translated_label) {
                                    $this->info($node->label);
                                    $this->warn($translated_label);
                                });

                            }
                        }
                    }
                    if (array_key_exists('description', $node)) {
                        if (array_key_exists($language, $node['description'])) {
                            $description = $node['description']['en'];
                            $translated_description = $node['description'][$language];
                            if ($translated_description != "" && $description != "") {
                                if ($this->argument('dry-run') == 0) {
                                    Node::where('medal_c_id', $medal_c_id)->each(function (Node $node) use ($translated_description) {
                                        if ($node->description != $translated_description) {
                                            $node->update(['description' => $translated_description]);
                                            $this->info($node->id . " updated");
                                        }
                                    });
                                } else {
                                    Node::where('medal_c_id', $medal_c_id)->each(function (Node $node) use ($translated_description) {
                                        $this->info($node->description);
                                        $this->warn($translated_description);
                                    });

                                }
                            }
                        }
                    }

                }
                $this->info("Processing answers");
                if (array_key_exists('answers', $node)) {
                    foreach ($node['answers'] as $answer) {
                        if (array_key_exists('id', $answer)) {
                            $medal_c_id = $answer['id'];
                            if (is_array($answer['label']) && array_key_exists($language, $answer['label'])) {
                                $translated_label = $answer['label'][$language];
                                $label = $answer['label']['en'];
                                if ($translated_label != "" && $label != "") {
                                    if ($this->argument('dry-run') == 0) {
                                        Answer::where('medal_c_id', $medal_c_id)->each(function (Answer $answer) use ($translated_label) {
                                            if ($answer->label != $translated_label) {
                                                $answer->update(['label' => $translated_label]);
                                                $this->info($answer->id . " updated");
                                            }
                                        });
                                    } else {
                                        Answer::where('medal_c_id', $medal_c_id)->each(function (Answer $answer) use ($translated_label) {
                                            $this->info($answer->label);
                                            $this->warn($translated_label);
                                        });
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $this->info("Processing diagnose");
            foreach ($diagnoses as $diagnose) {
                if (array_key_exists('id', $diagnose)) {
                    $medal_c_id = $diagnose['id'];
                    $label = $diagnose['label']['en'];

                    if (array_key_exists($language, $diagnose['label'])) {
                        $translated_label = $diagnose['label'][$language];
                        if ($translated_label != "" && $label != "") {
                            if ($this->argument('dry-run') == 0) {
                                Diagnosis::where('diagnostic_id', $medal_c_id)->each(function (Diagnosis $diagnosis) use ($translated_label) {
                                    if ($diagnosis->label != $translated_label) {
                                        $diagnosis->update(['label' => $translated_label]);
                                        $this->info($diagnosis->id . " updated");
                                    }
                                });
                            } else {
                                Diagnosis::where('diagnostic_id', $medal_c_id)->each(function (Diagnosis $diagnosis) use ($translated_label) {
                                    $this->info($diagnosis->label);
                                    $this->warn($translated_label);
                                });
                            }
                        }
                    }
                }
            }
        });
        $this->info("Translation done");
    }
}
