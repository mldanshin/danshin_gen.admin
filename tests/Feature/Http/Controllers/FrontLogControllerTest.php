<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FrontLogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider("providerInvoke")]
    public function testInvoke(string $message): void
    {
        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->post(route("log"), ["message" => $message]);
        $response->assertStatus(200);
    }

    /**
     * @return array[]
     */
    public static function providerInvoke(): array
    {
        return [
            ["Error"],
            ["Exception message"]
        ];
    }
}
