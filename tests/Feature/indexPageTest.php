<?php

namespace Tests\Feature;

use Tests\TestCase;

class indexPageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndexPage(): void
    {
        $response = $this->get(route('index'));
        $response->assertOk();
    }
}
