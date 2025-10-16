<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Shared\Auth\AuthService;
use Tests\TestCase;

class CarsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();

        require base_path('routes/ApiRoutes/CarRoute.php');
        $this->authService = app(AuthService::class);
    }
    public function test_example(): void
    {
        $user = User::find(1);
        $token = $this->authService->createAccessToken($user['id']);
        $responsePost = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/v1/cars', [
                    "brand" => "test Mercedes Benz",
                    "model" => "testC240",
                    "number" => "test 2444DD02" . rand(10000, 99999),
                    "color" => "test metall"
                ]);

        $responsePost->assertStatus(200)->assertJson([
            'code' => 0,
        ]);

        $car = json_decode($responsePost->getContent());

        $responseList = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/v1/cars');

        $responseList->assertStatus(200)->assertJson([
            'code' => 0,
        ]);

        $responseItem = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/v1/cars/' . $car->data->id);

        $responseItem->assertStatus(200)->assertJson([
            'code' => 0,
        ]);

        $responseUpdate = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put('/v1/cars/' . $car->data->id, [
                    "brand" => "changed test Mercedes Benz",
                    "model" => "changed testC240",
                    "number" => "changed test 2444DD02" . rand(10000, 99999),
                    "color" => "changed test metall"
                ]);

        $responseUpdate->assertStatus(200)->assertJson([
            'code' => 0,
        ]);

        $responseDelete = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->delete('/v1/cars/' . $car->data->id);

        $responseDelete->assertStatus(200)->assertJson([
            'code' => 0,
        ]);
    }
}
