<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function when_not_authenticated_category_page_redirect_to_login_page(): void
    {
        $response = $this->get(route('categories.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('business-login'));
    }
}
