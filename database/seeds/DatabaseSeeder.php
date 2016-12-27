<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notification_notification_group')->delete();
        DB::table('notification_groups')->delete();
        DB::table('notifications')->delete();
        DB::table('notification_type_user')->delete();
        DB::table('notification_types')->delete();
        DB::table('permissions')->delete();
        DB::table('reports')->delete();
        DB::table('error_duplicates')->delete();
        DB::table('errors')->delete();
        DB::table('versions')->delete();
        DB::table('mappings')->delete();
        DB::table('projects')->delete();
        DB::table('users')->delete();
        DB::table('environments')->delete();
        DB::table('languages')->delete();
        DB::table('systems')->delete();
        DB::table('groups')->delete();

        $this->call(GroupsTableSeeder::class);
        $this->call(SystemTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(EnvironmentTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        $this->call(VersionTableSeeder::class);
        $this->call(ErrorsTableSeeder::class);
        $this->call(ReportsTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(NotificationTypesTableSeeder::class);
        $this->call(NotificationTypeUserTableSeeder::class);
    }
}
