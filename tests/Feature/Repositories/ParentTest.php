<?php

namespace Tests\Feature\Repositories;

use App\Exceptions\ApiException;
use App\Exceptions\BadRequestException;
use App\Models\Person\ParentAviable;
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

    #[DataProvider("providerParentsAviableByRequestSuccess")]
    public function testParentsAviableByRequestSuccess(
        ParentCreatedRequestModel $request,
        string $query,
        ?array $fakeResponse,
        ParentAviable $expected
    ): void {
        Http::fake([
            config("app.api.url") . "/api/parent/possible" . $query => Http::response($fakeResponse, 200),
        ]);

        $actual = new ParentRepository()->getParentsAviableByRequest($request);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return mixed[]
     */
    public static function providerParentsAviableByRequestSuccess(): array
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
                new ParentAviable(
                    null,
                    1,
                    collect([
                        (object) [
                            "id" => 2,
                            "surname" => "fake"
                        ]
                    ])
                )
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
                "?person_id=1&birth_date=2020-04-15&marriages%5B0%5D%5Bperson%5D=10&marriages%5B1%5D%5Bperson%5D=16&role_id=13",
                [
                    [
                        "id" => 2,
                        "surname" => "fake"
                    ]
                ],
                new ParentAviable(
                    null,
                    13,
                    collect([
                        (object) [
                            "id" => 2,
                            "surname" => "fake"
                        ]
                    ])
                )
            ]
        ];
    }

    public function testParentsAviableByRequestSuccessNull(): void {
        Http::fake([
            config("app.api.url") . "/api/parent/possible" => Http::response([], 200),
        ]);

        $actual = new ParentRepository()->getParentsAviableByRequest(
                new ParentCreatedRequestModel(
                null,
                null,
                null,
                collect()
            )
        );

        $this->assertEquals(null, $actual);
    }

    public function testParentsAviableByRequestWrong422(): void
    {
        Http::fake([
            config("app.api.url") . "/api/parent/possible?role_id=3" => Http::response(status: 422),
        ]);

        $this->expectException(BadRequestException::class);
        new ParentRepository()->getParentsAviableByRequest(
            new ParentCreatedRequestModel(
                null,
                null,
                3,
                collect()
            )
        );
    }

    public function testParentsAviableByRequestWrong500(): void
    {
        Http::fake([
            config("app.api.url") . "/api/parent/possible?role_id=3" => Http::response(status: 500),
        ]);

        $this->expectException(ApiException::class);
        new ParentRepository()->getParentsAviableByRequest(
            new ParentCreatedRequestModel(
                null,
                null,
                3,
                collect()
            )
        );
    }

    #[DataProvider("providerParentsAviableByPersonSuccess")]
    public function testParentsAviableByPersonSuccess(
        object $person,
        string $query,
        ?array $fakeResponse,
        Collection $expected
    ): void {
        Http::fake([
            config("app.api.url") . "/api/parent/possible" . $query => Http::response($fakeResponse, 200),
        ]);

        $actual = new ParentRepository()->getParentsAviableByPerson($person);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return mixed[]
     */
    public static function providerParentsAviableByPersonSuccess(): array
    {
        return [
            [
                (object) [
                    "isUnavailable" => false,
                    "isLive" => true,
                    "gender" => 2
                ],
                "?role_id=1",
                [
                    [
                        "id" => 2,
                        "surname" => "fake"
                    ]
                ],
                collect()
            ],
            [
                (object) [
                    "id" => 1,
                    "isUnavailable" => false,
                    "isLive" => true,
                    "gender" => 2,
                    "surname" => "Ivanov",
                    "name" => "Ivan",
                    "patronymic" => "Ivanovich",
                    "hasPatronymic" => true,
                    "birthDate" => (object) [
                        "hasUnknown" => false,
                        "isEmpty" => false,
                        "string" => "2000-10-12"
                    ],
                    "parents" => [
                        (object) [
                            "person" => 2,
                            "role" => 1
                        ],
                    ],
                    "marriages" => [
                        (object) [
                            "role" => 2,
                            "soulmate" => 4,
                            "soulmateRole" => 4
                        ],
                    ]
                ],
                "?person_id=1&birth_date=2000-10-12&marriages%5B0%5D%5Bperson%5D=4&role_id=1",
                [
                    [
                        "id" => 2,
                        "surname" => "fake"
                    ]
                ],
                collect([
                    new ParentAviable(
                        2,
                        1,
                        collect([
                            (object) [
                                "id" => 2,
                                "surname" => "fake"
                            ]
                        ])
                    )
                ])
            ],
            [
                (object) [
                    "id" => 1,
                    "isUnavailable" => false,
                    "isLive" => true,
                    "gender" => 2,
                    "parents" => [
                        (object) [
                            "person" => 2,
                            "role" => 1
                        ],
                    ]
                ],
                "?person_id=1&role_id=1",
                [
                    [
                        "id" => 2,
                        "surname" => "fake"
                    ]
                ],
                collect([
                    new ParentAviable(
                        2,
                        1,
                        collect([
                            (object) [
                                "id" => 2,
                                "surname" => "fake"
                            ]
                        ])
                    )
                ])
            ]
        ];
    }

    public function testParentsAviableByPersonWrong422(): void
    {
        Http::fake([
            config("app.api.url") . "/api/parent/possible?person_id=1&role_id=1" => Http::response(status: 422),
        ]);

        $this->expectException(BadRequestException::class);
        new ParentRepository()->getParentsAviableByPerson(
            (object) [
                "id" => 1,
                "isUnavailable" => false,
                "isLive" => true,
                "gender" => 2,
                "parents" => [
                    (object) [
                        "person" => 2,
                        "role" => 1
                    ],
                ],
            ],
        );
    }

    public function testParentsAviableByPersonWrong500(): void
    {
        Http::fake([
            config("app.api.url") . "/api/parent/possible?person_id=1&role_id=1" => Http::response(status: 500),
        ]);

        $this->expectException(ApiException::class);
        new ParentRepository()->getParentsAviableByPerson(
            (object) [
                "id" => 1,
                "isUnavailable" => false,
                "isLive" => true,
                "gender" => 2,
                "parents" => [
                    (object) [
                        "person" => 2,
                        "role" => 1
                    ],
                ],
            ],
        );
    }
}
