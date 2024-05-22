<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


use App\Models\User;

/**
 * Refresh a token.
 *
 * @return \Illuminate\Http\JsonResponse
 */
class AuthController extends Controller
{
    use ApiResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'phone_number' => 'required|regex:/^\+?([0-9]{1,3})\)?([0-9]{6,14})$/',
            'password' => 'required|string|min:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
    
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password),
             'role' => 'user'] // تعيين قيمة الدور إلى 'user' بشكل افتراضي
        ));
    
        // تعديل هنا لإضافة 'role' إلى البيانات المرجعة
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'role' => $user->role // إضافة 'role' هنا
        ], 201);
    

    return $this->successResponse($user, 'User registered successfully', 201);
}
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    // public function refresh() {
    //     return $this->createNewToken(auth()->refresh());
    // }
    // public function userProfile() {
    //     return response()->json(auth()->user());
    // }

    // protected function createNewToken($token){
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => auth()->factory()->getTTL() * 60,
    //         'user' => auth()->user()
    //     ]);
     }

