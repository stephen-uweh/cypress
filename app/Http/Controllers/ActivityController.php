<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    //

    public function index(Request $request){
        $startDate = Carbon::parse($request->startDate)->format('Y-m-d');
        $endDate = Carbon::parse($request->endDate)->format('Y-m-d');
        $activities = Activity::whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
        // $activities = Activity::all();
        return response()->json([
            'status' => 200,
            'data' => $activities
        ]);
    }

    public function show($id){
        $activity = Activity::findOrFail($id);
        return response()->json([
            'status' => 200,
            'data' => $activity
        ]);
    }

    public function add(Request $request){
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validation->fails()){
            return response()->json([
                'status' => 400,
                'message' => $validation->errors()
            ]);
        }

        $activity = Activity::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Activity added',
            'data' => $activity
        ]);
    }

    public function edit(Request $request, $id){
        $activity = Activity::findOrFail($id);

        $activity->update($request->all());

        return response()->json([
            'status' => 201,
            'message' => 'Activity updated',
            'data' => $activity
        ]);
    }

    public function test($date){
        $new = Carbon::parse($date)->format('d-m-Y');
        return response()->json([
            'date' => $new
        ]);
    }
}
