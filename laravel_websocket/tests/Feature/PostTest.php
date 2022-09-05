<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    public function test_post()
    {
        $response = $this->postJson('/api/login',['email'=>'master@gmail.com','password'=>'123456']);
        $response->assertStatus(200);
        $decoded = $response->decodeResponseJson();

        $posts = $this->withHeaders(['Authorization'=>'Bearer '.$decoded['access_token']])->getJson('/api/post');
        $posts->assertStatus(200);

        $data  = $posts->decodeResponseJson();
        foreach ($data['data'] as $key => $value) {
            if( !isset($value["id"])        || 
                !isset($value["user_id"])   || 
                !isset($value["title"])     || 
                !isset($value["content"])   ||  
                !isset($value["content"]) ){
                throw new \Exception("Missing Post data");
            }
        }
    }

}