<?php

use App\Models\FlatReport;
use App\Models\Language;
use App\Models\Report;
use App\Models\Error;
use App\Models\System;
use App\Models\Version;

class ReportsTableSeeder extends BaseSeeder
{
    private $errors;
    private $systems;
    private $languages;
    private $versions;

    protected $count = 0;
    protected $model = Report::class;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->errors = Error::with('project.versions')->get();
        $this->systems = System::all(['id']);
        $this->languages = Language::all(['id']);
        $this->count = config('app.env') == 'local' ? 500 : 0;

        parent::run();
    }

    /**
     * Generate random model record.
     *
     * @param \Faker\Generator $faker
     * @return array
     */
    protected function repeat(\Faker\Generator $faker = null, $i)
    {
        $results = [];
        $count = $faker->numberBetween(1, 3);

        $error = $this->errors->random();
        $default = [
            'client_id' => str_random(64),
            'client_ip' => $faker->ipv4,
            'error_id' => $error->id,
            'language_id' => $this->languages->random()->id,
        ];

        for ($j = 0; $j < $count; ++$j) {
            $reported_at = $faker->dateTimeBetween('-1 month', 'now');
            $results[] = array_merge($default, [
                'system_id' => $this->systems->random()->id,
                'version_id' => $error->project->versions->random()->id,
                'name' => $faker->sentence(6, true),
                'stack_trace' => $faker->sentence(20, true),
                'message' => $faker->boolean(5) ? $faker->sentence(3, true) : null,
                'reported_at_date' => $reported_at,
                'reported_at' => $reported_at,
            ]);
        }

        return $results;
    }
}
