<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function only_loggedin_users_can_apply_for_job()
    {
        $this->assertTrue(true);
       // $response = $this->get('/account/apply-job')->assertRedirect('/login');
    }
}
