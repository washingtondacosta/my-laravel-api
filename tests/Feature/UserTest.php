<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class UserTest extends TestCase
{
	
	use WithFaker;

	private $password = "mypassword";
	
	public function testUserCreation()
	{
		
		$name = $this->faker->name();
		$email = $this->faker->email();

		$response = $this->postJson('/api/auth/signup', [
			'name' => $name, 
			'email' => $email,
			'password' => $this->password, 
			'password_confirmation' => $this->password
		]); 


		$response
		->assertStatus(201)
		->assertExactJson([
			'message' => "Successfully created user!",
		]);
	}

	public function test_login(){

        $user = User::factory()->create();
        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => $user->password
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
	
}