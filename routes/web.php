<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    // LOGIN ROUTE
    $router->post('login', 'UserController@login');

    // TODO : post measure from Live Objects -> todo V3 middleware checking the origin of the request
    $router->post('measures', ['uses' => 'MeasureController@create']);

});

// USER ROUTES (BEARER AUTHENTICATION)
$router->group(["middleware" => "auth:api"], function () use ($router) {
    $router->group(['prefix' => 'api'], function () use ($router) {
        //USER ROUTES
        $router->patch('users/email', 'UserController@updateMail');
        $router->patch('users/password', 'UserController@updatePassword');
        // MEASURE ROUTES
        $router->get('users/{id}/measures', 'MeasureController@showMeasuresByUser');
        $router->get('users/{id_user}/boards/{id_board}', ['uses' => 'MeasureController@showMeasuresByBoard']);
        $router->get('lastopening', 'MeasureController@showOnOffSensor');
        //SENSOR ROUTES
        $router->get('users/{id}/boards/{id_board}/sensors', 'SensorController@showSensorsByBoard');

        //SENSOR TYPE ROUTES
        $router->get('sensortypes', 'SensorTypeController@showAllSensorTypes');

        // BOARD CONTROLLER
        $router->get('users/{id_user}/boards', ['uses'=>'BoardController@showBoardsByUser']);
    });

// ADMIN ROUTES
    $router->group(["middleware" => "scopes:admin"], function () use ($router) {
        $router->group(['prefix' => 'api'], function () use ($router) {
            //MEASURE ROUTES
            $router->get('users/{id}/measures/all', 'MeasureController@showAllMeasures');
            $router->get('boards/{id_board}', ['uses' => 'MeasureController@showMeasuresByBoardAdm']);
            // USER CONTROLLER
            $router->post('users', 'UserController@create');

            // BOARD CONTROLLER
            $router->post('boards', ['uses' => 'BoardController@create']);
            $router->get('boards', ['uses'=>'BoardController@showAllBoards']);

            //SENSOR ROUTES
            $router->get('boards/{id_board}/sensors', ['uses' => 'SensorController@showSensorsByBoardAdm']);


        });

    });

});


