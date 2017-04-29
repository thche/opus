<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateTeamTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $data = [
        'email'      => 'opus@info.com',
        'first_name' => 'opus',
        'last_name'  => 'admin',
        'team_name'  => 'opus',
        'password'   => 'opusadmin',
    ];

    /** @test */
    public function it_can_see_create_team_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('team.create')
                ->assertSee('Create a Team')
                ->assertSee('Email')
                ->assertInputValue('email', '')
                ->assertSee('First name')
                ->assertInputValue('first_name', '')
                ->assertSee('Last name')
                ->assertInputValue('last_name', '')
                ->assertSee('Password')
                ->assertInputValue('password', '')
                ->assertSee('Confirm Password')
                ->assertInputValue('password_confirmation', '');
        });
    }

    /** @test */
    public function it_can_create_team()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('team.create')
                ->assertSee('Create a Team')
                ->type('email', $this->data['email'])
                ->type('first_name', $this->data['first_name'])
                ->type('last_name', $this->data['last_name'])
                ->type('password', $this->data['password'])
                ->type('password_confirmation', $this->data['password'])
                ->type('team_name', $this->data['team_name'])
                ->click('.btn[value=Submit]')
                ->assertRouteIs('home');
        });

        $this->assertDatabaseHas('users', [
            'first_name' => $this->data['first_name'],
            'last_name'  => $this->data['last_name'],
            'email'      => $this->data['email'],
        ]);

        $this->assertDatabaseHas('teams', [
            'name' => $this->data['team_name'],
        ]);
    }
}
