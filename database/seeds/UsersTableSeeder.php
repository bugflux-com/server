<?php


use App\Models\User;
use Carbon\Carbon;

class UsersTableSeeder extends BaseSeeder
{
    private $password;

    protected $count = 0;
    protected $model = User::class;

    public function run()
    {
        $this->password = bcrypt('secret');
        $this->count = config('app.env') == 'local' ? 10 : 0;

        parent::run();
    }

    protected function always()
    {
        $now = Carbon::now();

        return [
            'id' => 1,
            'name' => 'root',
            'email' => 'root@bugflux.com',
            'password' => $this->password,
            'is_root' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    /**
     * Generate model record.
     *
     * @param \Faker\Generator $faker
     * @return array
     */
    protected function repeat(\Faker\Generator $faker = null, $i)
    {
        $name = $faker->name;
        $nick = str_slug($name);

        return [
            'id' => null,
            'name' => $name,
            'email' => "$nick@example.com",
            'password' => $this->password,
            'is_root' => false

        ];
    }
}
