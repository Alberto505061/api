<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 28/12/2018
 * Time: 14:27
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SensorType extends Model
{

    protected $fillable = [
        'code_type','label','unit'
    ];

    public function sensors(){
        return $this->hasMany(Sensor::class);
    }

    public $timestamps = false;
}