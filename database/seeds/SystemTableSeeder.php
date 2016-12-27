<?php

use App\Models\System;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SystemTableSeeder extends BaseSeeder
{
    protected $model = System::class;

    /**
     * List of the most popular desktop and mobile OS.
     *
     * @var array
     */
    private $systems = [
        'Windows 10',
        'Windows 8.1',
        'Windows 8',
        'Windows 7',
        'Windows XP',
        'Windows Vista',
        'Mac OS X',
        'iOS 7',
        'iOS 6',
        'iOS 5',
        'Android 5',
        'Android 4',
        'Android 3',
        'Ubuntu',
        'Debian',
        'CentOS',
        'Fedora',
        'Red Hat',
        'Arch Linux',
        'SUSE',
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->count = count($this->systems);
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
            'name' => $this->systems[$i],
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
