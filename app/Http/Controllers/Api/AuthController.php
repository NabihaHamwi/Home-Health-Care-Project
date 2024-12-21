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
    use ApiResponseTrait;


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => 'required|string|between:2,15',
            "last_name" => 'required|string|between:2,15',
            'email' => 'required|email|max:50|unique:users',
            'gender' => 'required|in:male,female',
            'password' => 'required|min:6',
            'phone_number' => ['required', 'unique:users', 'regex:/^(\\+|00)?\d{1,3}\d{6,10}$/']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        
        if ($request->has('role')) {
            $role = $request->role;
        } else {
            $role = "user";
        }

        $newuser = User::create([
            "email" => $request->email,
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "gender" => $request->gender,
            "phone_number" => $request->phone_number,
            "password" => hash::make($request->password),
            "role" => $role
        ]);
        return response()->json(['message' => 'تمت عملية انشاء الحساب بنجاح']);
    }
    public function login(Request $request)
    {
        // التحقق من صحة المدخلات
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // إذا فشلت التحقق من صحة المدخلات، إرجاع رسالة خطأ
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // البحث عن المستخدم بالبريد الإلكتروني
        $user = User::where('email', $request->email)->first();

        // التحقق من أن المستخدم موجود وأن كلمة المرور صحيحة
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['errors' => 'Invalid credentials'], 401);
        }

        // محاولة المصادقة باستخدام بيانات الاعتماد
        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json(['errors' => 'Unauthorized'], 401);
        }

        // الحصول على المستخدم المصادق عليه
        $user = auth()->user();

        // إعادة تعيين الجلسة باستخدام رمز JWT
        JWTAuth::setToken($token);
        JWTAuth::authenticate($token);

        // إرجاع استجابة نجاح مع رمز الوصول ومعلومات المستخدم
        return response()->json([
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'role' => $user->role
            ],
        ], 200);
    }

    public function logout()
    {
        // التحقق من تسجيل دخول المستخدم
        if (auth()->check()) {
            // تسجيل خروج المستخدم
            auth()->logout();

            // إرجاع رسالة نجاح في حالة تسجيل الخروج بنجاح
            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الخروج بنجاح'
            ], 200);
        }

        // إرجاع رسالة خطأ في حالة عدم تسجيل أي مستخدم الدخول
        return response()->json([
            'success' => false,
            'message' => 'لم يتم تسجيل دخول أي مستخدم'
        ], 401);
    }
}
