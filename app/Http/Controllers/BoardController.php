<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 14/03/2019
 * Time: 14:25
 */

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Board;
use App\Models\Sensor;
use App\Models\SensorType;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Support\Facades\DB;


class BoardController extends Controller
{
  /*
    public function showAllBoards()
    {
        return response()->json(Board::all());
    }

    public function showOneBoard($id)
    {
       return response()->json(Board::find($id));
    }
*/
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {

//        $this->validate($request, [
//            'address' => 'required'
//            'usermail' => 'required|email',
//            'deveui' => 'required',
//            'sensors' => 'required',
//        ]);

        $sensor_PM_types = [];
        $sensors_list = [];
        $sensor_item = []; // contains sensor's ID and type label
        $sensor_counter = 1;
        $user = User::where('email', $request['usermail'])->first();
        $testBoard = Board::where('deveui',$request['deveui'])->first();
        if ($testBoard === null) {
            if ($user !== null) {

                $board = new Board;
                $board->id_user = $user->id;
                $board->location = $request->address;
                $board->deveui = $request->deveui;
                $board->save();

                foreach ($request->sensors as $sensor) {

                    if ($sensor === 'Particules fines') {
                        $type1 = SensorType::where('code_type', '5d')->first();
                        array_push($sensor_PM_types, $type1);
                        $type2 = SensorType::where('code_type', '6d')->first();
                        array_push($sensor_PM_types, $type2);
                        $type3 = SensorType::where('code_type', '7d')->first();
                        array_push($sensor_PM_types, $type3);
                        for ($i = 0; $i < 3; $i++) {
                            $newsensor = Sensor::create([
                                'id_type' => $sensor_PM_types[$i]->id,
                                'id_board' => $board->id,
                                'id_sensor' => $sensor_counter,

                            ]);
                            $sensor_counter++;
                            $sensor_item['id'] = $newsensor->id_sensor;
                            $sensor_item['label'] = $sensor;
                            array_push($sensors_list, $sensor_item);

                        }

                    } else {
                        $type = SensorType::where('label', $sensor)->first();
                        if ($type !== null) {
                            $newsensor = Sensor::create([
                                'id_type' => $type->id,
                                'id_board' => $board->id,
                                'id_sensor' => $sensor_counter,

                            ]);
                            $sensor_counter++;
                            $sensor_item['id'] = $newsensor->id_sensor;
                            $sensor_item['label'] = $sensor;
                            array_push($sensors_list, $sensor_item);

                        }

                    }
                }
                return response()->json($sensors_list, 201);
            } else {
                return response()->json([
                    'error' => [
                        'code' => 400,
                        'message' => 'Utilisateur introuvable',
                    ]
                ], 400);
            }
        } else {
            return response()->json([
                'error' => [
                    'code' => 400,
                    'message' => 'Ce dev EUI existe déjà dans la base de données',
                ]
            ], 400);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function showBoardsByUser(Request $request, int $id){

        $user = $request->user();
        if ($user->id === $id)
        {
            $board = DB::table('boards')
                ->select('boards.id',
                    'boards.location')
                ->where('id_user', '=', $id)
                ->get();
        }
        else {
            abort(403, "Forbidden");
        }
        return response()->json($board);
    }



    public function showAllBoards()
    {
        return response()->json(Board::all());
    }



    //TODO: changer la fonction update pour pouvoir modifier les capteurs de la carte
//    public function update($id, Request $request)
//    {
//        $this->validate($request, [
//            'location' => 'required'
//        ]);
//        $board = Board::findOrFail($id);
//        $board->update($request->all());
//        return response()->json($board, 200);
////    }
//
//    public function delete($id)
//    {
//        Board::findOrFail($id)->delete();
//        return response('Deleted Successfully');
//    }


}