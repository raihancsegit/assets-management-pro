<?php

use Tests\TestCase;

class ExampleUnitTest extends TestCase
{
    /** @test */
    public function home_page_is_ok(): void
    {
        $this->get('/')->assertStatus(200);
    }
}
