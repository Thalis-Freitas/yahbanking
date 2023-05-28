<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_redirected_to_login_page_if_not_authenticated(): void
    {
        $response = $this->get(route('home'));

        $response->assertRedirect('/login');
    }
}
