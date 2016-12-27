<?php

use App\Models\Group;
use App\Models\Permission;
use App\Models\User;
use App\Models\Project;

class PermissionsTableSeeder extends BaseSeeder
{
    protected $model = Permission::class;

    private $users;
    private $groups;
    private $projects;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->users = User::all(['id']);
        $this->groups = Group::all(['id']);
        $this->projects = Project::all(['id']);

        $this->count = config('app.env') == 'local' ? User::count() : 0;
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
            'user_id' => $this->users[$i]->id,
            'group_id' => $this->groups->random()->id,
            'project_id' => $this->projects->random()->id,
        ];
    }
}
