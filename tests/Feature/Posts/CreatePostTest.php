<?php

namespace Tests\Feature\Posts;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\AssertableJsonString;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    /** @test */
    public function user_can_create_post_if_data_is_valid()
    {
        $data = [
            'title' => $this->faker->name,
            'body'  => $this->faker->text,
        ];

        $response = $this->json('POST', route('posts.store'), $data);
        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(fn (AssertableJson $json) => 
            $json->has('data')
        );

        $this->assertDatabaseHas('posts', [
            'title' => $data['title'],
            'body'  => $data['body'],
        ]);
    }

    /** @test */
    public function user_can_not_create_post_if_title_is_null()
    {
        $data = [
            'title' => '',
            'body'  => $this->faker->text,
        ];

        $response = $this->postJson(route('posts.store'), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson(fn (AssertableJson $json) => 
            $json->has('errors', fn (AssertableJson $json) => 
                $json->has('title')->etc()
            )->etc()
        );
    }
}
