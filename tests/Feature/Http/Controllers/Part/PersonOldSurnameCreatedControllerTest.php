<?php

namespace Tests\Feature\Http\Controllers\Part;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonOldSurnameCreatedControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testCreate200(): void
    {
        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route("part.person.old_surname.create"));
        $response->assertStatus(200);
    }

    public function testCreate401(): void
    {
        $response = $this->withSession()
            ->get(route("part.person.old_surname.create"));

        $response->assertStatus(401);
    }
}
