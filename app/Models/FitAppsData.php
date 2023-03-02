<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class FitAppsData extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'steps'];

    protected $connection = 'mongodb';

    protected $collection = 'fit_apps_data';
}
