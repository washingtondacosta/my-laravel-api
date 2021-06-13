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

	public function testUserLogin()
	{
		$name = $this->faker->name();
		$email = $this->faker->email();

		$user = new User([
			'name' => $name,
			'email' => $email,
			'password' => bcrypt($this->password)
		]);        
		
		$user->save();     
		
		$response = $this->postJson('/api/auth/login', [
			'email' => $email,
			'password' => $this->password
		]);

		
		$response->assertStatus(200);
		$this->assertAuthenticated();
	}
	
}