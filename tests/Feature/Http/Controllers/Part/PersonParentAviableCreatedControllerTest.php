<?php

namespace Tests\Feature\Http\Controllers\Part;

use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PersonParentAviableCreatedControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider("providerCreate200")]
    public function testCreate200(array $fakeResponse, string $query, callable $assert): void
    {
        Http::fake([
            config("app.api.url") . "/api/parent/possible" . $query => Http::response($fakeResponse, 200),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route("part.person.parent_aviable.create") . $query);
        $assert($response);
    }

    /**
     * @return mixed[]
     */
    public static function providerCreate200(): array
    {
        return [
            [
                [
                    [
                        "id" => 2,
                        "surname" => "Egorov",
                        "oldSurname" => [
                            "Danad"
                        ],
                        "name" => "Igori",
                        "patronymic" => null
                    ]
                ],
                "?role_id=1",
                function ($response) {
                    $response->assertStatus(200);
                    $response->assertSee("Egorov");
                    $response->assertSee("Danad");
                    $response->assertSee("Igori");
                }
            ]
        ];
    }

    public function testCreate401(): void
    {
        $response = $this->withSession()
            ->get(route("part.person.parent_aviable.create"));

        $response->assertStatus(401);
    }
}