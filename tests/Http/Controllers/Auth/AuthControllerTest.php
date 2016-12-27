<?php

use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testLogin()
    {
        $password = str_random(10);
        $user = factory(User::class, 'admin')->create([
            'password' => bcrypt($password),
            'is_blocked' => false,
        ]);

        $this->visit('/login')
            ->type($user->email, 'email')
            ->type($password, 'password')
            ->press('Sign in')
            ->seePageIs('/');
    }

    public function testLoginBlocked()
    {
        $password = str_random(10);
        $user = factory(User::class, 'admin')->create([
            'password' => bcrypt($password),
            'is_blocked' => true,
        ]);

        $this->visit('/login')
            ->type($user->email, 'email')
            ->type($password, 'password')
            ->press('Sign in')
            ->seePageIs('/login');
    }
}
