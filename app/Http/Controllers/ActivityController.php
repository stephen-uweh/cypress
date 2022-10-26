<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\User;
use App\Models\UserActivity;
use Carbon\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    //Global Activities

    public function index(Request $request){
        // $startDate = Carbon::parse($request->startDate)->format('Y-m-d');
        // $endDate = Carbon::parse($request->endDate)->format('Y-m-d');
        // $activities = Activity::whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
        $activities = Activity::orderBy('created_at', 'asc')->get();
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

    public function addGlobal(Request $request){
        // Validation
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

        // Check for maximum activities in a day

        $date = Carbon::parse($request->date)->format('Y-m-d');

        $allActivities = Activity::where('date', $date)->get();
        if(count($allActivities) > 3){
            return response()->json([
                'status' => 400,
                'message' => 'Maximum  number of activities per day reached'
            ]);
        }


        // Create global activities
        if ($request->file('image')){
            
            $filePath = Cloudinary::uploadFile($request->file('image')->getRealPath())->getSecurePath();
        }

        $activity = Activity::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $filePath,
            'date' => $request->date
        ]);

        $users = User::all();
        
        if(count($users) > 0){
            foreach($users as $user){
                UserActivity::create([
                    'userId' => $user->id,
                    'activityId' => $activity->id,
                    'title' => $activity->title,
                    'description' => $activity->description,
                    'image' => $filePath,
                    'date' => $activity->date
                ]);
            }
        }

        return response()->json([
            'status' => 201,
            'message' => 'Activity added',
            'data' => $activity
        ]);
    }



    public function editGlobal(Request $request, $id){
        $activity = Activity::findOrFail($id);


        // Check for an image
        if ($request->file('image')){
            
            $filePath = Cloudinary::uploadFile($request->file('image')->getRealPath())->getSecurePath();
        }
        $request->image = $filePath;

        // Update Activity
        $activity->update($request->all());

        $userActivities = UserActivity::findOrFail($id);

        foreach($userActivities as $userActivity){
            $userActivity->update($request->all());
        }

        return response()->json([
            'status' => 201,
            'message' => 'Activity updated',
            'data' => $activity
        ]);
    }


    public function deleteGlobal($id){
        $activity = Activity::findOrFail($id);

        $activity->delete();

        $userActivities = UserActivity::findOrFail($id);

        foreach($userActivities as $userActivity){
            $userActivity->delete();
        }

        return response()->json([
            'status' => 201,
            'message' => "Activity Deleted"
        ]);
    }



    // User Activities

    public function allUserActivities(){
        $userId = auth()->user()->id;
        $userActivities = UserActivity::where('userId', $userId)->orderBy('date', 'asc')->get();

        return response()->json([
            'status' => 200,
            'data' => $userActivities
        ]);
    }


    public function showUserActivity($id){
        $userActivity = UserActivity::findOrFail($id);

        return response()->json([
            'status' => 200,
            'data' => $userActivity
        ]);
    }


    public function addActivityForUser(Request $request){
        $userId = auth()->user()->id;

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

        if ($request->file('image')){
            
            $filePath = Cloudinary::uploadFile($request->file('image')->getRealPath())->getSecurePath();
        }

        $userActivity = UserActivity::create([
            'userId' => $userId,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $filePath,
            'date' => $request->date
        ]);

        return response()->json([
            'status' => 201,
            'data' => $userActivity
        ]);
    }
}
