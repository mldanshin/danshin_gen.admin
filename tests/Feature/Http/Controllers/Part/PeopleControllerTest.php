<?php

namespace Tests\Feature\Http\Controllers\Part;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PeopleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider("providerShow200")]
    public function testShow200(
        string $query,
        string $queryHttp,
        array $fakeResponse,
        callable $assert
    ): void {
        Http::fake([
            config("app.api.url") . "/api/people" . $queryHttp => Http::response($fakeResponse, 200),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route("part.people.show") . $query);
        $assert($response);
    }

    /**
     * @return mixed[]
     */
    public static function providerShow200(): array
    {
        return [
            [
                "",
                "?order=name",
                [
                    [
                        "id" => 1,
                        "surname" => "Ivanov",
                        "oldSurname" => null,
                        "name" => "Ivan",
                        "patronymic" => "Ivanovich"
                    ],
                ],
                function ($response) {
                    $response->assertStatus(200);
                    $response->assertSee("Ivanov");
                    $response->assertSee("Ivan");
                    $response->assertSee("Ivanovich");
                    $response->assertSee(route('person.edit', ['person' => 1]));
                }
            ],
            [
                "?search=fake&order=age",
                "?search=fake&order=age",
                [
                    [
                        "id" => 45,
                        "surname" => "Egorov",
                        "oldSurname" => [
                            "Ifake1",
                            "Fake23"
                        ],
                        "name" => "Ivan",
                        "patronymic" => "Petrovich"
                    ],
                    [
                        "id" => 33,
                        "surname" => null,
                        "oldSurname" => null,
                        "name" => null,
                        "patronymic" => null
                    ],
                ],
                function ($response) {
                    $response->assertStatus(200);
                    $response->assertSee("Egorov");
                    $response->assertSee("Ifake1");
                    $response->assertSee("Fake23");
                    $response->assertSee("Ivan");
                    $response->assertSee("Petrovich");
                    $response->assertSee(assert("/person/45/edit"));
                    $response->assertSee(__("people.person.surname_unknown"));
                    $response->assertSee(__("people.person.name_unknown"));
                    $response->assertSee(__("people.person.patronymic_unknown"));
                    $response->assertSee(assert("/person/33/edit"));
                }
            ]
        ];
    }

    #[DataProvider("providerShow302")]
    public function testShow302(string $query): void
    {
        Http::fake([
            config("app.api.url") . "/api/people" . $query => Http::response(302),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route("part.people.show") . $query);
            $response->assertStatus(302);
    }

    /**
     * @return mixed[]
     */
    public static function providerShow302(): array
    {
        return [
            ["?order=fake"]
        ];
    }

    public function testShow401(): void
    {
        $response = $this->withSession()
            ->get(route("part.people.show"));

        $response->assertStatus(401);
    }

    public function testShow422(): void
    {
        Http::fake([
            config("app.api.url") . "/api/people?order=name" => Http::response(status: 422),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route("part.people.show"));
            $response->assertStatus(422);
    }

    public function testShow500(): void
    {
        Http::fake([
            config("app.api.url") . "/api/people?order=name" => Http::response(status: 500),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route("part.people.show"));
            $response->assertStatus(500);
    }
}
