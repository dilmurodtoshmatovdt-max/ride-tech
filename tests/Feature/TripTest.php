<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class TripTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    protected function setUp(): void
    {
        parent::setUp();

        require base_path('routes/ApiRoutes/TripRoute.php');
        require base_path('routes/ApiRoutes/CarRoute.php');
    }
    public function test_example(): void
    {
        $driver = User::find(1);
        $passenger = User::find(2);

        //Create Trip
        $responsePost = $this->actingAs($passenger, 'api')
            ->post('v1/trips', [
                'from_address' => 'test from address',
                'to_address' => 'test to address',
                'preferences' => 'air condition'
            ]);

        $responsePost->assertStatus(200)->assertJson([
            'code' => 0,
        ]);

        $trip = json_decode($responsePost->getContent());

        //Update Trip
        $responseUpdate = $this->actingAs($passenger, 'api')
            ->put('v1/trips/' . $trip->data->id, [
                'from_address' => 'changed test from address',
                'to_address' => 'changed test to address',
                'preferences' => 'changed air condition'
            ]);

        $responseUpdate->assertStatus(200)->assertJson([
            'code' => 0,
        ]);

        $responseCar = $this->actingAs($driver, 'api')
            ->get('v1/cars');

        $responseCar->assertStatus(200)->assertJson([
            'code' => 0,
        ]);

        $cars = json_decode($responseCar->getContent());

        //Assign Trip
        $responseAssign = $this->actingAs($driver, 'api')
            ->put('v1/trips/' . $trip->data->id . '/assign', ['car_id' => $cars->data[0]->id]);

        $responseAssign->assertStatus(200)->assertJson([
            'code' => 0,
        ]);

        //Arrive Trip
        $responseArrive = $this->actingAs($driver, 'api')
            ->put('v1/trips/' . $trip->data->id . '/arrive');

        $responseArrive->assertStatus(200)->assertJson([
            'code' => 0,
        ]);

        //Start Trip
        $responseStart = $this->actingAs($driver, 'api')
            ->put('v1/trips/' . $trip->data->id . '/start');

        $responseStart->assertStatus(200)->assertJson([
            'code' => 0,
        ]);

        //Finish Trip
        $responseFinish = $this->actingAs($driver, 'api')
            ->put('v1/trips/' . $trip->data->id . '/finish');

        $responseFinish->assertStatus(200)->assertJson([
            'code' => 0,
        ]);

        //Cancel Trip By Passenger
        $responseCancel = $this->actingAs($passenger, 'api')
            ->put('v1/trips/' . $trip->data->id . '/cancel');

        $responseCancel->assertStatus(200)->assertJson([
            'code' => -8,
        ]);

        //Reject Trip By Driver
        $responseReject = $this->actingAs($driver, 'api')
            ->put('v1/trips/' . $trip->data->id . '/reject');

        $responseReject->assertStatus(200)->assertJson([
            'code' => -8,
        ]);
    }
}
