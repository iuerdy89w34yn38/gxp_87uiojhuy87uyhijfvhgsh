<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeSetting extends Model
{
    protected $guarded = ['id'];
    protected $table = "time_settings";
}
