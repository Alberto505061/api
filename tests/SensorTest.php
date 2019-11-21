<?php

use App\Models\Board;
use App\Models\Sensor;
use App\Models\User;
use Laravel\Passport\Passport;

/**
 * Created by PhpStorm.
 * User: eleve
 * Date: 20/05/2019
 * Time: 16:15
 */
class SensorTest extends TestCase
{
    /**
     *
     * GET users/{id_user}/boards/{id_board}/sensors
     *
     */
    public function testShowSensorsByBoard()
    {
        $user = User::inRandomOrder()->firstOrFail();
        Passport::actingAs($user, ['user']);
        $board = Board::where('id_user', $user->id)->firstOrFail();
        $this->json("get", "api/users/{$user->id}/boards/{$board->id}/sensors");


        $this->assertResponseOk();

        // assert that each measure in the json response has below attributes
        $this->seeJsonStructure([
            '*' => [
                'id_sensor', 'deveui', 'location', 'label'
            ]
        ]);
    }

//    public function testDelete()
//    {
//        $sensor = Sensor::inRandomOrder()->firstOrFail();
//        $nbsensors = Sensor::all()->count();
//
//        $this->json("delete", "api/sensors/" . $sensor->id);
//
//
//        $this->assertEquals(Sensor::all()->count(), $nbsensors -1);
//        $this->assertNull(Sensor::where('id',$sensor->id));
//    }

}