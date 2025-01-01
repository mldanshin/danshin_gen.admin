<?php

namespace Tests\Feature\Http\Controllers\Part;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PersonParentCreatedControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('providerCreate200')]
    public function test_create200(
        array $fakeResponse,
        string $queryApi,
        string $query,
        callable $assert
    ): void {
        Http::fake([
            config('app.api.url').'/api/parent/possible'.$queryApi => Http::response($fakeResponse, 200),
        ]);
        Http::fake([
            config('app.api.url').'/api/parent/roles' => Http::response([
                '1' => 'mother',
                '2' => 'dad',
            ], 200),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route('part.person.parent.create').$query);
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
                        'id' => 2,
                        'surname' => 'Egorov',
                        'oldSurname' => [
                            'Danad',
                        ],
                        'name' => 'Igori',
                        'patronymic' => null,
                    ],
                ],
                '?birth_date=2024-01-01&marriages%5B0%5D%5Bperson%5D=1&role_id=1',
                '?birth_date=2024-01-01&marriages[]=1&role_id=1',
                function ($response) {
                    $response->assertStatus(200);
                    $response->assertSee(__('person.parents.items.1'));
                    $response->assertSee('Egorov');
                    $response->assertSee('Danad');
                    $response->assertSee('Igori');
                },
            ],
        ];
    }

    public function test_create401(): void
    {
        $response = $this->withSession()
            ->get(route('part.person.parent.create'));

        $response->assertStatus(401);
    }
}
