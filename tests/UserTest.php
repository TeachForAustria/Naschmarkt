<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;


    /**
     * Test user registration as staff.
     */
    public function testRegisterUser()
    {
        $user = factory(App\User::class, 'staff')->make();

        $this
            ->actingAs($user)
            ->visit('/register')
            ->type('Max Mustermann', 'name')
            ->type('max@mustermann.com', 'email')
            ->check('is_staff')
            ->press('Benutzer anlegen')
            ->seeInDatabase('users', [
                'name' => 'Max Mustermann',
                'email' => 'max@mustermann.com',
                'is_staff' => 1,
            ]);
    }

    /**
     * Test user registration as non-staff user.
     */
    public function testRegisterUserAsNonStaff()
    {
        $user = factory(App\User::class)->make();
        try {
            $this
                ->actingAs($user)
                ->visit('/register');
        } catch (\Illuminate\Foundation\Testing\HttpException $e) {
            $this->assertContains("Received status code [403]",$e->getMessage());
        }
    }

    /**
     * Test user registration as not logged in user.
     */
    public function testRegisterUserNotLoggedIn()
    {
        $user = factory(App\User::class)->make();
        try {
            $this
                ->actingAs($user)
                ->visit('/register');
        } catch (\Illuminate\Foundation\Testing\HttpException $e) {
            $this->assertContains("Received status code [403]",$e->getMessage());
        }
    }
}
