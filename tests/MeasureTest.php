<?php

use App\Models\Board;
use App\Models\User;
use Laravel\Passport\Passport;

/**
 * Created by PhpStorm.
 * User: eleve
 * Date: 20/05/2019
 * Time: 15:32
 */
class MeasureTest extends TestCase
{
    /**
     *
     * GET /api/users/{id}/measures
     *
     */
    public function testShowMeasuresOfUser()
    {
        $user = User::inRandomOrder()->firstOrFail();
        Passport::actingAs($user, ['user']);
        $this->json("get", "api/users/{$user->id}/measures");


        $this->assertResponseOk();

        // assert that each measure in the json response has below attributes
        $this->seeJsonStructure([
            '*' => [
                'id', 'value', 'id_sensor', 'id_board', 'id_user', 'location', 'label', 'unit', 'created_at'
            ]
        ]);
    }

    /**
     *
     * GET /api/users/{id_user}/boards/{id_board}
     *
     */
    public function testShowMeasuresOfBoard()
    {
        $user = User::inRandomOrder()->firstOrFail();
        Passport::actingAs($user, ['user']);
        $board = Board::where('id_user', $user->id)->firstOrFail();

        $this->json("get", "api/users/{$user->id}/boards/{$board->id}");

        $this->assertResponseOk();

        // assert that each measure in the json response has below attributes
        $this->seeJsonStructure([
            '*' => [
                'id', 'value', 'id_user', 'location', 'label', 'unit', 'created_at'
            ]
        ]);
    }
    /**
     *
     * POST api/measures
     *
     */
//    public function testCreateMeasure()
//    {
//        //TODO:fonction de test de création de mesure
//    }
}