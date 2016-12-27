<?php

use App\Models\NotificationType;
use App\Models\Permission;
use App\Models\Project;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Database\Seeder;

class NotificationTypeUserTableSeeder extends BaseSeeder
{
    protected $table = 'notification_type_user';

    private $notification_invalid_login_attempt_id = null;
    private $notification_new_privilege_id = null;
    private $notification_changed_privilege_id = null;
    private $notification_new_error_id = null;
    private $users = null;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->users = User::all('id');
        $this->count = count($this->users);
        $this->notification_invalid_login_attempt_id = NotificationType::where('code', 'invalid_login_attempt')->first()->id;
        $this->notification_new_privilege_id = NotificationType::where('code', 'new_privilege')->first()->id;
        $this->notification_changed_privilege_id = NotificationType::where('code', 'changed_privilege')->first()->id;
        $this->notification_new_error_id = NotificationType::where('code', 'new_error')->first()->id;
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
        $array = [
            [
                'user_id' => $this->users[$i]->id,
                'wantable_id' => null,
                'wantable_type' => null,
                'internal' => true,
                'email' => true,
                'notification_type_id' =>$this->notification_invalid_login_attempt_id,
            ],
            [
                'user_id' => $this->users[$i]->id,
                'wantable_id' => null,
                'wantable_type' => null,
                'internal' => true,
                'email' => true,
                'notification_type_id' =>$this->notification_new_privilege_id,
            ],
            [
                'user_id' => $this->users[$i]->id,
                'wantable_id' => null,
                'wantable_type' => null,
                'internal' => true,
                'email' => true,
                'notification_type_id' =>$this->notification_changed_privilege_id,
            ]

        ];

        if(config('app.env') == 'local') {
            $wantable_type_project = array_search(Project::class, AppServiceProvider::$morphMap);
            $user_permissions = Permission::where('user_id', $this->users[$i]->id)->get();
            foreach ($user_permissions as $permission) {
                array_push($array, [
                    'user_id' => $this->users[$i]->id,
                    'wantable_id' => $permission->project_id,
                    'wantable_type' => $wantable_type_project,
                    'internal' => true,
                    'email' => true,
                    'notification_type_id' => $this->notification_new_error_id,
                ]);
            }
        }

        return $array;
    }
}
