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
class UserController extends Controller
{
    use ApiResponseTrait;
    public function validateUserData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => 'required|string|between:2,15',
            "last_name" => 'required|string|between:2,15',
            'email' => 'required|email|max:50|unique:users',
            'gender' => 'required|in:male,female',
            'password' => 'required|min:6',
            'phone_number' => [
                'required',
                'unique:users',
                'unique:users',
                //'regex:/^\+?[1-9]\d{1,14}$/'
                'regex:/^(\\+|00)?\d{1,3}\d{6,10}$/'
            ]
        ]);

        if ($validator->fails()) {
            response()->json(['errors' => $validator->errors()], 400)->send();
            exit;
        }
    }

    public function register(Request $request)
    {
        $this->validateUserData($request);
        try {
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
            return response()->json(['message' => 'تمت عملية انشاء الحساب بنجاح', 'data' => $newuser], 200);
        } catch (\Exception $e) {
            // إذا حدث خطأ أثناء إنشاء المستخدم، أرسل رسالة خطأ
            return response()->json([
                'message' => 'فشل في إنشاء الحساب. حدث خطأ أثناء معالجة الطلب.',
                'error' => $e->getMessage()
            ], 500)->send();
            exit;
        }
    }
    /**********************************************/
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

        $customClaims = ['user_id' => $user->id];

        // إنشاء رمز JWT مع تضمين الـ payload المخصص
        $token = JWTAuth::claims($customClaims)->attempt($request->only('email', 'password'));
        
        if (!$token) {
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
        ], 200)->cookie('laravel_session', $request->session()->getId(), 120);
    }
    /***********************************************/
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
    /*********************************************/
    //retrieve user's full name
    public function getUserFullName($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            //dd($user);
            $full_name = $user->first_name . ' ' . $user->last_name;
            // dd( $full_name);
            return response()->json([
                'full_name' => $full_name,
                'message' => 'scusses'
            ] , 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage()
            ], 500)->send();
            exit;
        }
    }
    /*************************************************/
}
