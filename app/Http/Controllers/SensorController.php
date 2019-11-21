<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller;


class SensorController extends Controller
{
//    public function showAllSensors(){
//        return response()->json(Sensor::all());
//
//    }
//
//    public function showOneSensor($id){
//        return response()->json(Sensor::find($id));
//
//    }


    /**
     * @param $id_board
     * @return \Illuminate\Http\JsonResponse
     */
    public function showSensorsByBoardAdm(Request $request, $id_board)
    {
        $user = $request->user();
        if ($user->status === 'admin') {
            $sensors = DB::table('sensors')
                ->join('sensor_types', 'sensor_types.id', '=', 'sensors.id_type')
                ->join('boards', 'sensors.id_board', '=', 'boards.id')
                ->select(
                    'boards.deveui',
                    'sensors.id_sensor',
                    'boards.location',
                    'sensor_types.label')
                ->where('id_board', '=', $id_board)
                ->get();
        }
        return response()->json($sensors);

    }

    /**
     * @param $id
     * @param $id_board
     * @return \Illuminate\Http\JsonResponse
     */
    public function showSensorsByBoard($id, $id_board)
    {
            $sensors = DB::table('sensors')
                ->join('sensor_types', 'sensor_types.id', '=', 'sensors.id_type')
                ->join('boards', 'sensors.id_board', '=', 'boards.id')
                ->select(
                    'boards.deveui',
                    'sensors.id_sensor',
                    'boards.location',
                    'sensor_types.label')
                ->where('id_user','=',$id)
                ->where('id_board', '=', $id_board)
                ->get();

            return response()->json($sensors);

    }

//    public function create(Request $request){
//
//        $this->validate($request, [
//           'id_type' => 'required|integer',
//            'id_board' => 'required|integer',
//           'status' => 'required|in:allumé,éteint'
//        ]);
//
//        $sensor = Sensor::create($request->all());
//        return response()->json($sensor,201);
//    }
//
//    public function update($id, Request $request){
//        $this->validate($request, [
//
//            'status' => 'in:allumé,éteint'
//
//        ]);
//
//        $sensor = Sensor::findOrFail($id);
//        $sensor->update($request->all());
//        return response()->json($sensor, 200);
//    }
//
//    public function delete($id){
//        Sensor::findOrFail($id)->delete();
//        return response('Deleted Successfully', 200);
//    }



}
