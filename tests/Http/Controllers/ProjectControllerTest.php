<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create project and redirect to project page.
     */
    public function testCreate()
    {
        $name = str_random(10);
        $user = factory(User::class, 'admin')->make();

        $this->be($user);
        $this->visit('projects/create')
            ->type($name, 'name')
            ->press('Create')
            ->see($name);
    }

    /**
     * Redirect to login page when guest tries to create project.
     */
    public function testCreateUnauthorized()
    {
        $this->get('projects/create')
            ->assertRedirectedTo('login');
    }

    /**
     * Edit project and redirect to project page.
     */
    public function testEdit()
    {
        $name = str_random(10);
        $user = factory(User::class, 'admin')->make();
        $project = factory(Project::class)->create();

        $this->be($user);
        $this->visit("projects/{$project->id}/edit")
            ->type($name, 'name')
            ->press('Edit')
            ->seePageIs("projects/{$project->id}")
            ->see($name);
    }
}
