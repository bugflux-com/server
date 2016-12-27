<?php

use App\Models\Environment;
use App\Models\Error;
use App\Models\Project;
use App\Models\Version;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ErrorsTableSeeder extends BaseSeeder
{
    private $projects;
    private $environments;

    protected $count = 0;
    protected $model = Error::class;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->projects = Project::all(['id']);
        $this->environments = Environment::all(['id']);
        $this->count = config('app.env') == 'local' ? 80 : 0;

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
        return [
            'project_id' => $this->projects->random()->id,
            'environment_id' =>$this->environments->random()->id,
            'hash' => str_random(64),
            'name' => $faker->sentence(6, true)
        ];
    }
}
