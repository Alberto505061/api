<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 13/03/2019
 * Time: 10:45
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Board extends Model
{
    protected $fillable = [
        'location'
    ];

    public function user(){
        return $this->belongsTo(Board::class, 'id_user');
    }

    public $timestamps = false;

    public function sensors(){
        return $this->hasMany(Sensor::class, 'id_board');
    }

    public function measures(){
        return $this->hasManyThrough(Measure::class, Sensor::class,
            'id_board', 'id_sensor');
    }
}