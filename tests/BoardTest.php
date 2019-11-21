<?php

use App\Models\Board;
use App\Models\Sensor;
use App\Models\User;
use Laravel\Passport\Passport;

/**
 * Created by PhpStorm.
 * User: eleve
 * Date: 20/05/2019
 * Time: 14:04
 */
class BoardTest extends TestCase
{
    protected $board;

    /**
     *
     * POST /api/users
     *
     */

    public function testCreate()
    {
        $newBoard = factory(Board::class)->raw();
        $user = User::where('id', $newBoard['id_user'])->firstOrFail();
        Passport::actingAs($user, ['admin']);
        $nbSensors = Sensor::all()->count();

        $deveui = $newBoard['deveui'];
        $location = $newBoard['location'];

        $addRequest = '
        {
        "deveui" : "' . $deveui . ' ", 
        "address" : "' . $location . '",
        "usermail" : "' . $user->email . '",
        "sensors" : ["Tension"]
        }
        ';

        $json = json_decode($addRequest, true);


        $this->json("post", "api/boards",
            $json);

        $this->assertResponseStatus(201);
        $this->seeInDatabase("boards", $newBoard);
        $this->assertEquals(Sensor::all()->count(), $nbSensors + 1);

    }

//    public function testDelete()
//    {
//        $board = Board::inRandomOrder()->firstOrFail();
//        $nbBoards = Board::all()->count();
//        echo Board::all()->count();
//        $this->json("delete", "api/boards/" . $board->id);
//
//        echo Board::all()->count();
//        $this->assertEquals(Board::all()->count(), $nbBoards -1);
//    }

}