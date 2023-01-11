<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {
        $response = $this->postJson('/api/login',['email'=>'master@gmail.com','password'=>'123456']);
        $response->assertStatus(200);
        $decoded = $response->decodeResponseJson();

        $me = $this->withHeaders(['Authorization'=>'Bearer '.$decoded['access_token']])->getJson('/api/usuario/me');
        $me->assertStatus(200);
        $me->assertJson([
            'id'=>$decoded['user']['id'],
            'email'=>$decoded['user']['email'],
            'name'=>$decoded['user']['name'],
        ]);

    }
}
