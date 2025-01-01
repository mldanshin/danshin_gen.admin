<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PersonDeletedControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('provider200')]
    public function test200(int $personId): void
    {
        Http::fake([
            config('app.api.url')."/api/person/$personId" => Http::response([], 200),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->delete(route('person.destroy', ['person' => $personId]));
        $response->assertStatus(200);
    }

    /**
     * @return mixed[]
     */
    public static function provider200(): array
    {
        return [
            [
                12,
            ],
        ];
    }

    public function test401(): void
    {
        $response = $this->withSession()
            ->delete(route('person.destroy', ['person' => 12]));

        $response->assertStatus(401);
    }

    public function test404(): void
    {
        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->delete(route('person.destroy', ['person' => 'fake']));
        $response->assertStatus(404);
    }

    public function test500(): void
    {
        Http::fake([
            config('app.api.url').'/api/person/1' => Http::response(status: 500),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->delete(route('person.destroy', ['person' => 1]));
        $response->assertStatus(500);
    }
}
