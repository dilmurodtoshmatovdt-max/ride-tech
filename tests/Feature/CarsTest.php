<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CarsTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    protected function setUp(): void
    {
        parent::setUp();

        require base_path('routes/ApiRoutes/CarRoute.php');
    }
    public function test_example(): void
    {

        $driver = User::find(1);

        //Add Car
        $responsePost = $this->actingAs($driver, 'api')
            ->post('api/v1/cars', [
                "brand" => "test Mercedes Benz",
                "model" => "testC240",
                "number" => "test 2444DD02" . rand(10000, 99999),
                "color" => "test metall"
            ]);
        //Add Car
        $responsePost = $this->actingAs($driver, 'api')
            ->post('api/v1/cars', [
                "brand" => "test Mercedes Benz",
                "model" => "testC240",
                "number" => "test 2444DD02" . rand(10000, 99999),
                "color" => "test metall"
            ]);

        $responsePost->assertStatus(200)->assertJson([
            'code' => 0,
        ]);

        $car = json_decode($responsePost->getContent());

        //Car List
        $responseList = $this->actingAs($driver, 'api')
            ->get('api/v1/cars');

        $responseList->assertStatus(200)->assertJson([
            'code' => 0,
        ]);

        //Car Item
        $responseItem = $this->actingAs($driver, 'api')
            ->get('api/v1/cars/' . $car->data->id);

        $responseItem->assertStatus(200)->assertJson([
            'code' => 0,
        ]);

        //Car Update
        $responseUpdate = $this->actingAs($driver, 'api')
            ->put('api/v1/cars/' . $car->data->id, [
                "brand" => "changed test Mercedes Benz",
                "model" => "changed testC240",
                "number" => "changed test 2444DD02" . rand(10000, 99999),
                "color" => "changed test metall"
            ]);

        $responseUpdate->assertStatus(200)->assertJson([
            'code' => 0,
        ]);

        //Car delete
        $responseDelete = $this->actingAs($driver, 'api')
            ->delete('api/v1/cars/' . $car->data->id);

        $responseDelete->assertStatus(200)->assertJson([
            'code' => 0,
        ]);
    }
}
