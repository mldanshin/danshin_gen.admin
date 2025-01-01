<?php

namespace Tests\Feature\Http\Controllers\Part;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PersonPhotoFileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('provider200')]
    public function test200(): void
    {
        $disk = Storage::disk('testing');

        Http::fake([
            config('app.api.url').'/api/photo/4/1.webp' => Http::response(
                file_get_contents($disk->path('files/spring.webp')),
                200
            ),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route('part.person.photo.file', [
                'personId' => 4,
                'fileName' => '1.webp',
            ]));
        $response->assertStatus(200);
    }

    /**
     * @return mixed[]
     */
    public static function provider200(): array
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

    public function test401(): void
    {
        $response = $this->withSession()
            ->get(route('part.person.photo.file', [
                'personId' => 1,
                'fileName' => 'fake',
            ]));

        $response->assertStatus(401);
    }
}
