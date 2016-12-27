<?php

use App\Models\Project;
use App\Models\Version;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VersionTableSeeder extends BaseSeeder
{
    private $projects;
    private $ver_cnt;
    protected $model = Version::class;

    /**
     * List of the first app versions.
     *
     * @var array
     */
    private $versions = [
        '0.1.0',
        '1.0.0',
        '1.4.26',
        '0.3.17'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->projects = Project::all(['id']);
        $this->ver_cnt = count($this->versions);
        $this->count = config('app.env') == 'local' ? count($this->projects) : 0;
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
        $data = [];
        $faker->unique($reset = true);

        for($j = 0; $j < $faker->numberBetween(1, $this->ver_cnt); ++$j) {
            $index = $faker->unique()->numberBetween(0, $this->ver_cnt - 1);

            $data[] = [
                'name' => $this->versions[$index],
                'project_id' => $this->projects[$i]->id,
            ];
        }

        return $data;
    }
}
