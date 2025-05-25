<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UpdatePostTest extends TestCase
{
    /** @test */
    public function user_can_update_post_if_post_exist()
    {
        $post = Post::factory()->create();
        $dataUpdate = [
            'title' => $this->faker->name,
            'body'  => $this->faker->text,
        ];

        $response = $this->json('PUT', route('posts.update', $post->id), $dataUpdate);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', fn(AssertableJson $json ) => 
                $json->has('title')->has('id')
            )
        );

        $this->assertDatabaseHas('posts', [
            'title' => $dataUpdate['title'],
            'body'  => $dataUpdate['body'],
        ]);
    }
}
