<?php

namespace Tests\Feature\Http\Controllers\Part;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonInternetCreatedControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_create200(): void
    {
        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route('part.person.internet.create'));
        $response->assertStatus(200);
    }

    public function test_create401(): void
    {
        $response = $this->withSession()
            ->get(route('part.person.internet.create'));

        $response->assertStatus(401);
    }
}
