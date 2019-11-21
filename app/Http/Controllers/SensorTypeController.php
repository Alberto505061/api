<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 14/03/2019
 * Time: 14:25
 */

namespace App\Http\Controllers;




use App\Models\SensorType;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class SensorTypeController extends Controller
{


    public function showAllSensorTypes(){
        return response()->json(SensorType::all());
    }
//
//    public function showOneSensorType($id){
//        return response()->json(SensorType::find($id));
//    }
//
//    public function create(Request $request){
//
//        $this->validate($request, [
//            'label' => 'required',
//            'unit' => 'required'
//        ]);
//
//        $sensortype = SensorType::create($request->all());
//        return response()->json($sensortype,201);
//    }
//
//    public function update($id, Request $request){
//        $this->validate($request,[
//            'label' => 'required',
//            'unit' => 'required'
//        ]);
//
//        $sensortype = SensorType::findOrFail($id);
//        $sensortype->update($request->all());
//        return response()->json($sensortype,200);
//    }
//
//    public function delete($id){
//        SensorType::findOrFail($id)->delete();
//        return response('Deleted Successfully');
//    }



}