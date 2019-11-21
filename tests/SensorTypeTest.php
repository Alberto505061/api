<?php

use App\Models\SensorType;
use App\Models\User;
use Laravel\Passport\Passport;

/**
 * Created by PhpStorm.
 * User: eleve
 * Date: 22/05/2019
 * Time: 13:44
 */
class SensorTest extends TestCase
{
    /**
     *
     * GET /api/sensortypes
     *
     */
    public function testShowAllSensorTypes()
    {
        $user = User::inRandomOrder()->firstOrFail();
        Passport::actingAs($user, ['user']);
        $this->json("get", "api/sensortypes");

        $this->assertResponseOk();

        // assert that each measure in the json response has below attributes
        $this->seeJsonStructure([
            '*' => [
                'id', 'code_type', 'label', 'unit', 'created_at', 'updated_at'
            ]
        ]);
    }

//    /**
//     *
//     * POST /api/sensortypes
//     *
//     */
//    public function testCreate()
//    {
//        $sensortype = factory(SensorType::class)->raw();
//
//        $this->json("post", "api/sensortypes");
//
//        $this->assertResponseOk();
//        $this->seeInDatabase('sensor_type', $sensortype);
//    }
}