<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 28/12/2018
 * Time: 14:27
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Sensor extends Model
{
    protected $fillable = [
        'id_type', 'id_board', 'id_sensor'// , 'status'
    ];

    protected $hidden = [];

    public function measures(){
        return $this->hasMany(Measure::class, 'id_sensor');

    }

    public function board(){
        return $this->belongsTo(Board::class, 'id_board');
    }

    public function type(){
        return $this->belongsTo(SensorType::class, 'id_type');
    }
}