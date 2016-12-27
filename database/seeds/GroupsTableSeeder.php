<?php

use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GroupsTableSeeder extends BaseSeeder
{

    protected $model = Group::class;

    private $groups = array(
        'Project Manager' => 'pm',
        'Developer' => 'dev',
        'Tester' => 'tester',
        'Client' => 'client'
    );

    /**
     * Generate random model record.
     *
     * @return array
     */
    protected function always()
    {
        $now = Carbon::now();

        $items = [];
        foreach($this->groups as $name => $tag) {
            $items[] = [
                'name' => $name,
                'tag' => $tag,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        return $items;
    }
}
