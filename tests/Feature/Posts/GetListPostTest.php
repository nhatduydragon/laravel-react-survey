<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GetListPostTest extends TestCase
{
    /** @test */
    public function user_can_get_list_posts()
    {
        $totalPost = Post::count();

        $response = $this->getJson(route('posts.index'));
        $response->assertStatus(Response::HTTP_OK);

        // $response->assertJson(fn (AssertableJson $json) => 
        //     $json
        //     ->has('data', fn (AssertableJson $json) => 
        //             $json
        //             ->has('data')
        //             ->has('meta', fn (AssertableJson $json) => 
        //                 $json->where('total', $totalPost)
        //             )
        //             ->etc()
        //         )
        //     ->has('status')
        // );
    }
}
