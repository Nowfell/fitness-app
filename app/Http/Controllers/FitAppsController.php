<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FitAppsData;
use Carbon\Carbon;
use DB;

class FitAppsController extends Controller
{
    public function daily_step(Request $request) {
        $userId = auth()->user()->id;
        $date = date('Y-m-d');
        $validate = $request->validate([
            'steps' => 'required|integer|min:0',
        ]);

        $fitAppsData = FitAppsData::updateOrCreate(
            ['user_id' => $userId, 'date' => $date],
            ['steps' => $validate['steps']]
        );

        return response()->json([
            'message' => 'Steps saved successfully',
        ]);
    }

    public function leaderboard() {
        $userId = auth()->user()->id;

        $currentDate = date('Y-m-d');

        $fitAppsDatas = FitAppsData::where('date', $currentDate)->get();

        $fitAppsDatas = $fitAppsDatas->sortByDesc('steps');

        $userFitAppsData = $fitAppsDatas->where('user_id', $userId)->first();

        $userPosition = $fitAppsDatas->pluck('user_id')->search($userId) + 1;

        $userRankings = $fitAppsDatas->take(3);

        $closestPositionsAbove = collect([]);
        $closestPositionsBelow = collect([]);
        $offset = 1;
        while (($closestPositionsAbove->count() + $closestPositionsBelow->count()) < 6) {
            if ($userPosition - $offset >= 1) {
                $closestPositionsAbove->push($userPosition - $offset);
            }
            if ($userPosition + $offset <= $fitAppsDatas->count()) {
                $closestPositionsBelow->push($userPosition + $offset);
            }
            $offset++;
        }

        return [
            'user' => [
                'position' => $userPosition,
                'steps' => $userFitAppsData->steps,
            ],
            'top_rankings' => $userRankings->map(function ($fitAppsData) {
                return [
                    'user_id' => $fitAppsData->user_id,
                    'steps' => $fitAppsData->steps,
                ];
            }),
            'closest_positions_above' => $closestPositionsAbove,
            'closest_positions_below' => $closestPositionsBelow->reverse(),
        ];
    }

    public function steps()
    {
        $userId = auth()->user()->id;

        $fitAppsData = FitAppsData::where('user_id', $userId)->orderBy('date', 'asc')->get();

        $groupedData = $fitAppsData->groupBy('date');

        $mappedData = $groupedData->map(function ($group) {

            $stepsCount = $group->sum('steps');

            $date = Carbon::parse($group->first()->date)->format('Y-m-d');

            $startTime = $date . ' 00:00:00';
            $endTime = $date . ' 23:59:00';

            return [
                'stepsCount' => $stepsCount,
                'startTime' => $startTime,
                'endTime' => $endTime,
            ];
        });

        return response()->json($mappedData->values());
    }
}
