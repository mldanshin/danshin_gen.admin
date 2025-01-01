<?php

namespace Tests\Feature\Http\Controllers\Part;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test200(): void
    {
        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route('part.index'));
        $response->assertStatus(200);
        $response->assertSee(route('person.create'));
    }

    public function test401(): void
    {
        $response = $this->withSession()
            ->get(route('part.index'));

        $response->assertStatus(401);
    }
}
