<?php

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends BaseSeeder
{
    protected $count = 0;
    protected $model = Project::class;
    protected $codes = array();

    public function run()
    {
        $this->count = config('app.env') == 'local' ? 6 : 0;
        for($i = 0; $i < $this->count; $i++)
        {
            while (in_array($code = Project::random_code(), $this->codes));
            $this->codes[] = $code;
        }

        parent::run();
    }

    protected function repeat(\Faker\Generator $faker = null, $i)
    {
        return [
            'name' => $faker->company,
            'code' => $this->codes[$i]
        ];
    }
}
