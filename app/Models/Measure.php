<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 13/03/2019
 * Time: 10:56
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Measure extends Model
{
    protected $fillable = [
        'id_sensor', 'value'
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class,'id_sensor');
    }


}