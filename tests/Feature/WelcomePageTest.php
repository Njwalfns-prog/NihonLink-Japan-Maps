<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WelcomePageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function navbar_displays_brand_and_login_link()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('J-Map');
        $response->assertSee('Login');
    }
}
