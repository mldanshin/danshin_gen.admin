<?php

namespace Tests\Feature\Repositories;

use App\Exceptions\ApiException;
use App\Models\Person\ParentCreatedRequest as ParentCreatedRequestModel;
use App\Repositories\ParentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class ParentTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testRoleAllSuccess(): void
    {
        $fakeResponse = [
            "1" => "mother",
            "2" => "dad"
        ];

        Http::fake([
            config("app.api.url") . "/api/parent/roles" => Http::response($fakeResponse, 200),
        ]);

        $actual = new ParentRepository()->getRolesAll();

        $this->assertEquals($fakeResponse, $actual->all());
    }

    public function testRoleAllWrong(): void
    {
        Http::fake([
            config("app.api.url") . "/api/parent/roles" => Http::response(status: 500),
        ]);

        $this->expectException(ApiException::class);
        new ParentRepository()->getRolesAll();
    }

    #[DataProvider("providerParentsAviableSuccess")]
    public function testParentsAviableSuccess(
        ParentCreatedRequestModel $request,
        string $query,
        ?array $fakeResponse,
        Collection $expected
    ): void {
        Http::fake([
            config("app.api.url") . "/api/parent/possible" . $query => Http::response($fakeResponse, 200),
        ]);

        $actual = new ParentRepository()->getParentsAviable($request);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return mixed[]
     */
    public static function providerParentsAviableSuccess(): array
    {
        return [
            [
                new ParentCreatedRequestModel(
                    null,
                    null,
                    1,
                    collect()
                ),
                "?role_id=1",
                [
                    [
                        "id" => 2,
                        "surname" => "fake"
                    ]
                ],
                collect([
                    (object) [
                        "id" => 2,
                        "surname" => "fake"
                    ]
                ])
            ],
            [
                new ParentCreatedRequestModel(
                    1,
                    "2020-04-15",
                    13,
                    collect([
                        10,
                        16
                    ])
                ),
                "?person_id=1&birth_date=2020-04-15&mariages%5B0%5D%5Bperson%5D=10&mariages%5B1%5D%5Bperson%5D=16&role_id=13",
                [
                    [
                        "id" => 2,
                        "surname" => "fake"
                    ]
                ],
                collect ([
                    (object) [
                        "id" => 2,
                        "surname" => "fake"
                    ]
                ])
            ]
        ];
    }

    public function testParentsAviableSuccessNull(): void {
        Http::fake([
            config("app.api.url") . "/api/parent/possible" => Http::response([], 200),
        ]);

        $actual = new ParentRepository()->getParentsAviable(
                new ParentCreatedRequestModel(
                null,
                null,
                null,
                collect()
            )
        );

        $this->assertEquals(null, $actual);
    }

    public function testParentsAviableWrong(): void
    {
        Http::fake([
            config("app.api.url") . "/api/parent/possible" => Http::response(status: 500),
        ]);

        $this->expectException(ApiException::class);
        new ParentRepository()->getParentsAviable(
            new ParentCreatedRequestModel(
                null,
                null,
                3,
                collect()
            )
        );
    }
}
