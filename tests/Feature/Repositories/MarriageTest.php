<?php

namespace Tests\Feature\Repositories;

use App\Exceptions\ApiException;
use App\Exceptions\BadRequestException;
use App\Models\Person\MarriageAviable;
use App\Models\Person\MarriageCreatedRequest;
use App\Repositories\Marriage as MarriageRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class MarriageTest extends TestCase
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
            "1" => "partner1",
            "2" => "partner23",
            "3" => "partner46"
        ];

        Http::fake([
            config("app.api.url") . "/api/marriage/roles" => Http::response($fakeResponse, 200),
        ]);

        $actual = new MarriageRepository()->getRolesAll();

        $this->assertEquals($fakeResponse, $actual->all());
    }

    public function testRoleAllWrong500(): void
    {
        Http::fake([
            config("app.api.url") . "/api/marriage/roles" => Http::response(status: 500),
        ]);

        $this->expectException(ApiException::class);
        new MarriageRepository()->getRolesAll();
    }

    public function testRoleByGenderSuccess(): void
    {
        $fakeResponse = [
            "1" => "partner1",
            "2" => "partner23",
            "3" => "partner46"
        ];

        Http::fake([
            config("app.api.url") . "/api/marriage/roles/2" => Http::response($fakeResponse, 200),
        ]);

        $actual = new MarriageRepository()->getRolesByGender(2);

        $this->assertEquals($fakeResponse, $actual->all());
    }

    public function testRoleByGenderWrong500(): void
    {
        Http::fake([
            config("app.api.url") . "/api/marriage/roles/2" => Http::response(status: 500),
        ]);

        $this->expectException(ApiException::class);
        new MarriageRepository()->getRolesByGender(2);
    }

    #[DataProvider("providerAviableByRequestSuccess")]
    public function testAviableByRequestSuccess(
        MarriageCreatedRequest $request,
        string $query,
        ?array $fakeResponse,
        object $expected
    ): void {
        Http::fake([
            config("app.api.url") . "/api/marriage/possible" . $query => Http::response($fakeResponse, 200),
        ]);

        $actual = new MarriageRepository()->getAviableByRequest($request);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return mixed[]
     */
    public static function providerAviableByRequestSuccess(): array
    {
        return [
            [
                new MarriageCreatedRequest(
                    null,
                    null,
                    1,
                    collect()
                ),
                "?role_id=1",
                [
                    "role" => 2,
                    "people" => [
                        [
                            "id" => 2,
                            "surname" => "fake"
                        ]
                    ]
                ],
                new MarriageAviable(
                    1,
                    null,
                    2,
                    [
                        (object) [
                            "id" => 2,
                            "surname" => "fake"
                        ]
                    ]
                )
            ],
            [
                new MarriageCreatedRequest(
                    1,
                    "2020-04-15",
                    13,
                    collect([
                        10,
                        16
                    ])
                ),
                "?person_id=1&birth_date=2020-04-15&parents%5B0%5D%5Bperson%5D=10&parents%5B1%5D%5Bperson%5D=16&role_id=13",
                [
                    "role" => 2,
                    "people" => [
                        [
                            "id" => 2,
                            "surname" => "fake"
                        ]
                    ]
                ],
                new MarriageAviable(
                    13,
                    null,
                    2,
                    [
                        (object) [
                            "id" => 2,
                            "surname" => "fake"
                        ]
                    ]
                )
            ]
        ];
    }

    public function testAviableByRequestSuccessNull(): void {
        Http::fake([
            config("app.api.url") . "/api/marriage/possible" => Http::response([], 200),
        ]);

        $actual = new MarriageRepository()->getAviableByRequest(
                new MarriageCreatedRequest(
                null,
                null,
                null,
                collect()
            )
        );

        $this->assertEquals(null, $actual);
    }

    public function testAviableByRequestWrong422(): void
    {
        Http::fake([
            config("app.api.url") . "/api/marriage/possible?role_id=3" => Http::response(status: 422),
        ]);

        $this->expectException(BadRequestException::class);
        new MarriageRepository()->getAviableByRequest(
            new MarriageCreatedRequest(
                null,
                null,
                3,
                collect()
            )
        );
    }

    public function testAviableByRequestWrong500(): void
    {
        Http::fake([
            config("app.api.url") . "/api/marriage/possible?role_id=3" => Http::response(status: 500),
        ]);

        $this->expectException(ApiException::class);
        new MarriageRepository()->getAviableByRequest(
            new MarriageCreatedRequest(
                null,
                null,
                3,
                collect()
            )
        );
    }

    #[DataProvider("providerAviableByPersonSuccess")]
    public function testAviableByPersonSuccess(
        object $person,
        string $query,
        ?array $fakeResponse,
        object $expected
    ): void {
        Http::fake([
            config("app.api.url") . "/api/marriage/possible" . $query => Http::response($fakeResponse, 200),
        ]);

        $actual = new MarriageRepository()->getAviableByPerson($person);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return mixed[]
     */
    public static function providerAviableByPersonSuccess(): array
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
                    "role" => 2,
                    "people" => [
                        [
                            "id" => 2,
                            "surname" => "fake"
                        ]
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
                        "string" => "2020-04-15"
                    ],
                    "parents" => [
                        (object) [
                            "person" => 10,
                            "role" => 1
                        ],
                    ],
                    "marriages" => [
                        (object) [
                            "role" => 13,
                            "soulmate" => 4,
                            "soulmateRole" => 14
                        ],
                    ]
                ],
                "?person_id=1&birth_date=2020-04-15&parents%5B0%5D%5Bperson%5D=10&role_id=13",
                [
                    "role" => 14,
                    "people" => [
                        [
                            "id" => 4,
                            "surname" => "fake"
                        ]
                    ]
                ],
                collect([
                    new MarriageAviable(
                        13,
                        4,
                        14,
                        [
                            (object) [
                                "id" => 4,
                                "surname" => "fake"
                            ]
                        ]
                    )
                ])
            ],
            [
                (object) [
                    "id" => 1,
                    "isUnavailable" => false,
                    "isLive" => true,
                    "gender" => 2,
                    "marriages" => [
                        (object) [
                            "role" => 13,
                            "soulmate" => 4,
                            "soulmateRole" => 14
                        ],
                    ]
                ],
                "?person_id=1&role_id=13",
                [
                    "role" => 14,
                    "people" => [
                        [
                            "id" => 4,
                            "surname" => "fake"
                        ]
                    ]
                ],
                collect([
                    new MarriageAviable(
                        13,
                        4,
                        14,
                        [
                            (object) [
                                "id" => 4,
                                "surname" => "fake"
                            ]
                        ]
                    )
                ])
            ]
        ];
    }

    public function testAviableByPersonWrong422(): void
    {
        Http::fake([
            config("app.api.url") . "/api/marriage/possible?person_id=1&role_id=13" => Http::response(status: 422),
        ]);

        $this->expectException(BadRequestException::class);
        new MarriageRepository()->getAviableByPerson(
            (object) [
                "id" => 1,
                "isUnavailable" => false,
                "isLive" => true,
                "gender" => 2,
                "marriages" => [
                    (object) [
                        "role" => 13,
                        "soulmate" => 4,
                        "soulmateRole" => 14
                    ],
                ]
            ],
        );
    }

    public function testAviableByPersonWrong500(): void
    {
        Http::fake([
            config("app.api.url") . "/api/marriage/possible?person_id=1&role_id=13" => Http::response(status: 500),
        ]);

        $this->expectException(ApiException::class);
        new MarriageRepository()->getAviableByPerson(
            (object) [
                "id" => 1,
                "isUnavailable" => false,
                "isLive" => true,
                "gender" => 2,
                "marriages" => [
                    (object) [
                        "role" => 13,
                        "soulmate" => 4,
                        "soulmateRole" => 14
                    ],
                ]
            ],
        );
    }
}
