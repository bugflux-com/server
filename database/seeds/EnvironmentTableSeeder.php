<?php

use App\Models\Environment;
use Carbon\Carbon;

class EnvironmentTableSeeder extends BaseSeeder
{
    protected $model = Environment::class;

    private $environments= [
        'Development',
        'Testing',
        'Production'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->count = count($this->environments);
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
        $now = Carbon::now();

        return [
            'name' => $this->environments[$i],
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}