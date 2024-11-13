<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

use App\Models\User;

/**
 * Refresh a token.
 *
 * @return \Illuminate\Http\JsonResponse
 */
class AuthController extends Controller
{
   // use ApiResponseTrait;
    

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => 'required|string|between:2,15',
            "last_name" => 'required|string|between:2,15',
            'email' => 'required|email|max:50|unique:users',
          //  'gender' => 'required|in:male ,female',
            'password' => 'required|min:6',
            'phone_number' => ['required', 'unique:users', 'regex:/^(\\+|00)?\d{1,3}\d{6,10}$/']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        //التحقق من طلب المستخدم , قبل ادخال معلومات المستخدم لقاعدة البيانات
        if($request->has('role'))
        {
            $role = $request->role ;
        }
        else{
            $role ="user";
        }
        $newuser = User::create([
            "email" => $request->email,
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "gender" => $request->gender,
            "phone_number" => $request->phone_number,
            "password" => hash::make($request->password) ,
            "role" => $role
        ]);

        return Response('تمت عملية انشاء الحساب بنجاح');
    }

}
