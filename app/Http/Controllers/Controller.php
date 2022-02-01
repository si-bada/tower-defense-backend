<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function Save(Request $request)
    {
        // VALIDATION
        $validator = Validator::make($request->all(),
        [ 'id' => 'required', 'score' => 'required',  'level' => 'required', ]);
        
        if ($validator->fails())
        { 
            return response()->json([ 'error' => $validator->messages()->first() ], 400); 
        } 
        $user = User::where('id', $request->input('id'))->first();
        if($user == null)
            return response()->json([ 'error' => ('user does not exist !') ], 500); 

        try {
            //code...
            $score = $request->score;
            $level = $request->level;
            $user->score = $score;
            $user->level_reached = $level;
            $user->save();
            return response()->json(($user), 200);
        } catch (\Throwable $th) {

            return response()->json([ 'error' => ('an eroor occured !') ], 500); 
        }
    }
}
