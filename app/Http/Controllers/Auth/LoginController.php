<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;

class LoginController extends Controller
{
    public function Login(Request $request)
    {
        // VALIDATION
        $validator = Validator::make($request->all(),
            [ 'password' => 'required', 'username' => 'required|string|max:50']);
            if ($validator->fails())
            { 
                return response()->json([ 'error' => $validator->messages()->first() ], 400); 
            } 
        // GETTING INPUT
            $username = $request->input('username');
            $password = $request->input('password');
            $user = User::where('username', $username)->first();

            if ($user == null) {
                return response()->json(['error' => ('Invalid Credentials')], 400);
            }
    
            if (Hash::check($password, $user->password) == false) {
                return response()->json(['error' => ('Invalid Credentials')], 400);
            }
            try {
                $user->save();
                return response()->json(($user), 200);
            } catch (\Throwable $th) {
                Log::debug('Error saving token:' . $th->getMessage());
            }
    }
}