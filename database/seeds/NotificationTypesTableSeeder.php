<?php

use App\Models\NotificationType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NotificationTypesTableSeeder extends BaseSeeder
{
    protected $model = NotificationType::class;

    private $codes = [
        'new_error',
        'new_report',
        'invalid_login_attempt',
        'new_error_comment',
        'new_report_comment',
        'new_privilege',
        'changed_privilege',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->count = count($this->codes);
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
            'code' => $this->codes[$i],
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
