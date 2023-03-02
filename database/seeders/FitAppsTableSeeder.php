<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FitAppsData;
use App\Models\User;

class FitAppsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            $fitAppsData = new FitAppsData();
            $fitAppsData->user_id = $user->id;
            $fitAppsData->date = date('Y-m-d');
            $fitAppsData->steps = rand(1000, 10000);
            $fitAppsData->save();
        }
    }
}
