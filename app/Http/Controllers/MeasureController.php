<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 14/03/2019
 * Time: 14:25
 */

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Board;
use App\Models\Measure;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class MeasureController extends Controller
{


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showMeasuresByUser(Request $request, int $id){

        $user = $request->user();
        if ($user->id === $id)
        {
            $measure = DB::table('measures')
                ->join('sensors', 'measures.id_sensor', '=', 'sensors.id')
                ->join('sensor_types', 'sensor_types.id', '=', 'sensors.id_type')
                ->join('boards', 'sensors.id_board', '=', 'boards.id')
                ->select('measures.id',
                    'measures.created_at',
                    'measures.value',
                    'measures.id_sensor',
                    'sensors.id_board',
                    'boards.id_user',
                    'boards.location',
                    'sensor_types.label',
                    'sensor_types.unit')
                ->where('id_user', '=', $id)
                ->where('sensor_types.label', '!=', 'Tout ou Rien')
                ->get();
        }
        else {
            abort(403, "Forbidden");
        }
        return response()->json($measure);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAllMeasures(Request $request, int $id){

        $user = $request->user();
        if ($user->id === $id)
        {
            $measure = DB::table('measures')
                ->join('sensors', 'measures.id_sensor', '=', 'sensors.id')
                ->join('sensor_types', 'sensor_types.id', '=', 'sensors.id_type')
                ->join('boards', 'sensors.id_board', '=', 'boards.id')
                ->select('measures.id',
                    'measures.created_at',
                    'measures.value',
                    'measures.id_sensor',
                    'sensors.id_board',
                    'boards.id_user',
                    'boards.location',
                    'sensor_types.label',
                    'sensor_types.unit')
                ->where('id_user', '>', '0')
                ->where('sensor_types.label', '!=', 'Tout ou Rien')
                ->get();
        }
        else {
            abort(403, "Forbidden");
        }
        return response()->json($measure);
    }

    /**
     * @param Request $request
     * @param int $id_board
     * @return \Illuminate\Http\JsonResponse
     */
    public function showMeasuresByBoardAdm(Request $request,int $id_board) {

        $user = $request->user();
        if ($user->status === 'admin'){
            $measure = DB::table('measures')
                ->join('sensors', 'measures.id_sensor', '=', 'sensors.id')
                ->join('sensor_types', 'sensor_types.id', '=', 'sensors.id_type')
                ->join('boards', 'sensors.id_board', '=', 'boards.id')
                ->select('measures.id',
                    'measures.created_at',
                    'measures.value',
                    'sensors.id_board',
                    'boards.id_user',
                    'boards.location',
                    'boards.deveui',
                    'sensor_types.label',
                    'sensor_types.unit')
                ->where('id_board', '=', $id_board)
                ->get();
        }
        else {
            abort(403, "Forbidden");
        }

        return response()->json($measure);
    }


    /**
     * @param Request $request
     * @param int $id_board
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showMeasuresByBoard(Request $request,int $id_board, int $id) {

        $user = $request->user();
        if ($user->id === $id) {
            $measure = DB::table('measures')
                ->join('sensors', 'measures.id_sensor', '=', 'sensors.id')
                ->join('sensor_types', 'sensor_types.id', '=', 'sensors.id_type')
                ->join('boards', 'sensors.id_board', '=', 'boards.id')
                ->select('measures.id',
                    'measures.created_at',
                    'measures.value',
                    'sensors.id_board',
                    'boards.id_user',
                    'boards.location',
                    'sensor_types.label',
                    'sensor_types.unit')
                ->where('id_user', '=', $id)
                ->where('id_board', '=', $id_board)
                ->get();
        }
        else {
            abort(403, "Forbidden");
        }

        return response()->json($measure);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * returns opened boxes less than one day old
     */
    public function showOnOffSensor(Request $request) {

        $user = $request->user();
        $measures = DB::table('measures')
            ->join('sensors', 'measures.id_sensor', '=', 'sensors.id')
            ->join('sensor_types', 'sensor_types.id', '=', 'sensors.id_type')
            ->join('boards', 'sensors.id_board', '=', 'boards.id')
            ->select('measures.id',
                'measures.created_at',
                'measures.value',
                'sensors.id_board',
                'boards.location',
                'sensor_types.label')
            ->where('id_user', '=', $user->id)
            ->where('code_type', '=', '71')
            ->where('measures.value', '=', 0)
            ->orderBy('measures.created_at','desc')
            ->get();


        $measures = $measures->filter(function($measure) {
            $measure->created_at = Carbon::parse($measure->created_at);

            $lastDate = Carbon::now()->subDays(1);
            return $measure->created_at->gt($lastDate);
        }
        );


        return response()->json($measures);
    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request) // create method used by LiveObjects end-node, post devEUI and not id_sensor
    {

        $this->validate($request, [
            'value.payload' => 'required | string',
            'metadata.network.lora.devEUI' => 'required | string'
        ]);

        $devEUI = $request['metadata']['network']['lora']['devEUI'];

        // payload = n°measure(1bytes) - id_sensor(1bytes) - data(1 or 2 bytes) - ...
        $payload = $request['value']['payload'];


        if(!empty($payload))
        {
            // Measures Array
            $measures = array();

            // String to Bytes Array format Payload
            $bytesPayload = $this->stringToBytesArray($payload);

            // Retrieve board id
            $id_board = Board::where('deveui', '=', $devEUI)
                ->firstOrFail()
                ->id;

            $byte = current($bytesPayload); // By default the internal array pointer is pointing to the 1st element
            $byte = hexdec($byte); // get decimal value
            while($byte !== false)
            {

                // Retrieve sensor id
                $id = Sensor::where('id_sensor', '=', $byte)
                    ->where('id_board', '=', $id_board)
                    ->firstOrFail()
                    ->id;
                // Retrieve code_type of sensor
                $code_type = Sensor::join('sensor_types', 'sensors.id_type', '=', 'sensor_types.id')
                    ->where('sensors.id', '=', $id)
                    ->firstOrFail()
                    ->code_type;


                // Retrieve measure's value

                // Battery level
                if($code_type == "72")
                {
                    // 1 byte data
                    $hexValue = next($bytesPayload) . next($bytesPayload);
                    $value = hexdec($hexValue) * 0.00080586 * 4.9; // quantum : 1 unit = 0.01294 V, /4.9 Voltage divider
                    if ($id_board=="34")
                    {
                        $value=12.15;
                    }
                }
                // Temperature
                elseif($code_type == "67")
                {
                    $hexValue = next($bytesPayload) . next($bytesPayload);
                    $value = (hexdec($hexValue) * 1000 * 0.00080586 / 10) - 5;  // 1°C = 10mV
                }

                // Distance
                elseif($code_type == "74")
                {
                    $hexValue = next($bytesPayload) . next($bytesPayload);
                    $value = (hexdec($hexValue) * 0.00080586 * 61.849) + 3.529; //Linear regression from Excel
                }

                // Water Level
                elseif($code_type == "75")
                {
                    $hexValue = next($bytesPayload) . next($bytesPayload);
                    $value = (hexdec($hexValue) * 0.00080586 * 81.967) - 5.492; //Linear regression from Excel
                }

                // Wind speed
                elseif($code_type == "76")
                {
                    $hexValue = next($bytesPayload) . next($bytesPayload);
                    $value = (hexdec($hexValue) * 0.00080586 * 6); // Datasheet's formula
                }

                // Soil Moisture (Humidity)
                elseif($code_type == "77")
                {
                    $hexValue = next($bytesPayload) . next($bytesPayload);
                    $value = (hexdec($hexValue) * 0.00080586 *(-52.743) ) + 212.03; // //Linear regression from Excel
                }

                // Luminosity
                elseif($code_type == "78")
                {
                    $hexValue = next($bytesPayload) . next($bytesPayload);
                    $value = 79.729 * pow(hexdec($hexValue) * 0.00080586,-2.09); // Exponential regression from Excel
                }

                // Sound level
                elseif($code_type == "79")
                {
                    $hexValue = next($bytesPayload) . next($bytesPayload);
                    $value = 7.9549 * log(hexdec($hexValue) * 0.00080586, M_E) + 77.507; //Logaritmic regression from Excel
                }

                // Current
                elseif($code_type == "70"){
                    // 1 byte data
                    $hexValue = next($bytesPayload) . next($bytesPayload);
                    $value = 0.0465 * hexdec($hexValue) * 0.00080586 - 0.0937; // Empiracal linear law (DC current measured with Tore sensor AC)
                }

                // Humidity
                elseif($code_type == "73"){
                    // 1 byte data
                    $hexValue = next($bytesPayload) . next($bytesPayload);
                    $value = ((hexdec($hexValue) * 0.00080586 / 3.3) - 0.16 ) / 0.0062; //linear law provided in HIH4000's datasheet
                }

                // Current DC
                elseif($code_type == "dc") {
                    // 1 byte data
                    $hexValue = next($bytesPayload) . next($bytesPayload);
                    $value = (hexdec($hexValue) * 0.00080586 * 1000 * 97.465) - 242.73; //Linear regression from Excel
                    //$value = (((hexdec($hexValue) * 0.00080586) * 1000) - 2563.44) * 0.09799;
                    //$value = hexdec($hexValue) * 0.00080586 * 1000;
                    if ($value > 0.2 && $value <-0.20){
                        $value = 0;
                    }
                    if($id_board === 31){
                        $value = (((hexdec($hexValue) * 0.00080586) * 1000) - 2577.946) * 0.09799;// Different offset and the multiplier is different because it's a 300A sensor
                        //if ($value < 0.20 && $value > -0.20){
                          //  $value = 0;
                        //}
                    }
                }

                // On / Off sensor
                elseif($code_type == "71") {
                    // 1 byte data
                    $hexValue = next($bytesPayload);
                    $value = hexdec($hexValue); // 0 : closed , 1 : opened
                }

                // PM sensors
                elseif ($code_type== "5d" || $code_type == "6d" || $code_type == "7d"){
                    // 2 bytes data
                    $hexValue = next($bytesPayload) . next($bytesPayload);
                    $value = hexdec($hexValue) * 0.1; // See PM sensor datasheet
                }
                else {
                    return "Unknown sensor type";
                }

                // Create and Store measure
                $measure = new Measure;
                $measure->id_sensor = $id;
                $measure->value = $value;
                $measure->save();
                array_push($measures, $measure); // add measure to measures array

                $byte = next($bytesPayload);
            }
        }
        return response()->json($measures, 201);
    }


    /**
     * @param string $word
     * @return array
     */
    public function stringToBytesArray( string $word )
    {
        $bytes = array();
        for($i = 0 ; $i < strlen($word)/2 ; $i++)
        {
            $bytes[$i] = $word[2*$i] . $word[2*$i + 1];
        }
        return $bytes;
    }
}