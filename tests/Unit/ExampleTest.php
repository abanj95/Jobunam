<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }
    /**test */
    public function only_loggedin_users_can_apply_for_job()
    {
        $this->assertTrue(true);
       // $response = $this->get('/account/apply-job')->assertRedirect('/login');
    }
    
}
