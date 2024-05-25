<?php

namespace App\Http\Controllers\Api;

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
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    //__________________________________________________________________________________________________________-

    public function login(Request $request)
    {
        // التحقق من صحة بيانات الإدخال
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'البريد الالكتروني مطلوب',
            'email.email' => 'يجب أن يكون البريد الإلكتروني عنوانًا صالحًا.',
            'password.required' => 'كلمة السر مطلوبة ادخل كلمة السر الخاصة بك للمتابعة',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        try {
            // محاولة الحصول على الرمز باستخدام بيانات الاعتماد المُصادق عليها
            if (!$token = JWTAuth::attempt($validator->validated())) {
                // إذا فشلت المصادقة، يتم إرجاع رسالة خطأ
                return $this->errorResponse('فشلت المصادقة تأكد من الايميل او كلمة السر', 401);
            }

            // الحصول على بيانات المستخدم
            $user = JWTAuth::user();
        } catch (JWTException $e) {
            // في حالة وجود خطأ أثناء إنشاء الرمز، يتم إرجاع رسالة خطأ
            return $this->errorResponse('حدث خطأ أثناء انشاء ال token', 500);
        }

        // إذا تم الحصول على الرمز بنجاح، يتم إرجاعه مع بيانات المستخدم في استجابة JSON
        return $this->successResponse(compact('user', 'token'), 200);
    }

    //_______________________________________________________________________________________________________________



    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'password' => 'required|string|min:6',
    //     ], [
    //         'email.required' => 'البريد الإلكتروني مطلوب.',
    //         'email.email' => 'يجب أن يكون البريد الإلكتروني عنوانًا صالحًا.',
    //         'password.required' => 'كلمة المرور مطلوبة.',
    //         'password.string' => 'يجب أن تكون كلمة المرور نصًا.',
    //         'password.min' => 'يجب أن تكون كلمة المرور على الأقل 6 أحرف.',
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->errorResponse($validator->errors()->toJson(), 422);
    //     }

    //     if (!$token = JWTAuth::attempt($validator->validated())) {
    //         return $this->errorResponse('Unauthorized', 401);
    //     }

    //     $user = auth()->user();
    //     return $this->successResponse([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => auth()->factory()->getTTL() * 60,
    //         'user' => $user,
    //         'role' => $user->role // إضافة قيمة الدور هنا
    //     ], 200);
    // }


    //____________________________________________________________________________________________________________

    // public function register(Request $request)
    // {
    //     // التحقق من صحة بيانات الإدخال
    //     $validator = Validator::make($request->all(), [
    //         'first_name' => 'required|string|between:2,100',
    //         'last_name' => 'required|string|between:2,100',
    //         'email' => 'required|string|email|max:100|unique:users',
    //         'phone_number' => ['required', 'regex:/^(\\+|00)?\d{1,3}\d{6,10}$/'],
    //         'password' => 'required|string|min:6',
    //     ], [
    //         'first_name.required' => 'الاسم الأول مطلوب.',
    //         'first_name.between' => 'يجب أن يكون الاسم الأول بين 2 و 100 حرف.',
    //         'last_name.required' => 'الاسم الأخير مطلوب.',
    //         'last_name.between' => 'يجب أن يكون الاسم الأخير بين 2 و 100 حرف.',
    //         'email.required' => 'البريد الإلكتروني مطلوب.',
    //         'email.email' => 'يجب أن يكون البريد الإلكتروني صالحًا.',
    //         'email.max' => 'يجب ألا يتجاوز البريد الإلكتروني 100 حرف.',
    //         'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
    //         'phone_number.required' => 'رقم الهاتف مطلوب.',
    //         'phone_number.regex' => 'تنسيق رقم الهاتف غير صالح.',
    //         'password.required' => 'كلمة المرور مطلوبة.',
    //         'password.min' => 'يجب أن تكون كلمة المرور على الأقل 6 أحرف.',
    //     ]);

    // إذا فشل التحقق من الصحة، يُرجع الأخطاء
    //     if ($validator->fails()) {
    //         return $this->errorResponse($validator->errors(), 400);
    //     }

    //     // إنشاء مستخدم جديد باستخدام البيانات المُصادق عليها
    //     $user = User::create(array_merge(
    //         $validator->validated(),
    //         [
    //             'password' => bcrypt($request->password),
    //             'role' => 'user' 
    //         ]
    //     )); 

    //     // إنشاء رمز JWT للمستخدم الجديد
    //     $token = JWTAuth::fromUser($user);

    //     // إرجاع استجابة JSON تحتوي على بيانات المستخدم والرمز المميز
    //     return $this->successResponse(compact('user', 'token'), "تم انشاء المستخدم", 200);
    // }

    //_____________________________________________________________________________________



    public function refreshToken()
    {
        try {
            if (!$token = JWTAuth::getToken()) {
                return $this->errorResponse('token_not_provided', 401);
            }

            // التحقق من الـ blacklist قبل التحديث
            if (JWTAuth::getPayload($token)->get('blacklisted')) {
                // الـ token مبطل والمستخدم مسجل خروج بالفعل
                return $this->errorResponse('token_blacklisted', 401);
                //           // تحقق من وجود الـ token وصلاحيته
                // if (Auth::guest() || !Auth::tokenValid()) {
                //     // إذا كان الـ token غير موجود أو غير صالح
                //     return response()->json(['error' => 'Token is invalid or missing'], 401);
                // }

            }

            $newToken = JWTAuth::refresh($token);

            return $this->successResponse(['token' => $newToken]);
        } catch (TokenInvalidException $e) {
            return $this->errorResponse('token_invalid', 401);
        } catch (TokenBlacklistedException $e) {
            // الـ token مبطل والمستخدم مسجل خروج بالفعل
            return $this->errorResponse('token_blacklisted', 401);
        } catch (JWTException $e) {
            return $this->errorResponse('could_not_refresh_token', 500);
        }
    }


    //_____________________________________________________________________________


    
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

    //_____________________________________________________________________________________
    // public function login(Request $request)
    // {
    //     // التحقق من صحة بيانات الإدخال
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'password' => 'required|string|min:6',
    //     ], [
    //         'email.required' => 'البريد الإلكتروني مطلوب.',
    //         'email.email' => 'يجب أن يكون البريد الإلكتروني عنوانًا صالحًا.',
    //         'password.required' => 'كلمة المرور مطلوبة.',
    //         'password.string' => 'يجب أن تكون كلمة المرور نصًا.',
    //         'password.min' => 'يجب أن تكون كلمة المرور على الأقل 6 أحرف.',
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->errorResponse($validator->errors(), 422);
    //     }


    //     $credentials = $request->only('email', 'password');

    //     try {
    //         if (!$token = JWTAuth::attempt($credentials)) {
    //             return response()->json(['error' => 'Unauthorized'], 401);
    //         }
    //     } catch (JWTException $e) {
    //         return response()->json(['error' => 'Could not create token'], 500);
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'token' => $token
    //     ]);
    // }

    public function register(Request $request)
    {
        // التحقق من صحة بيانات الإدخال
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'phone_number' => ['required', 'regex:/^(\\+|00)?\d{1,3}\d{6,10}$/'],
            'password' => 'required|string|min:6',
        ],);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => $request->password,
            'role' => 'user',
            'created_at' =>  now(),

        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        // استخدام Auth للتحقق من تسجيل دخول المستخدم
        // Auth::check() تعود بـ true إذا كان المستخدم مسجل الدخول
        if (Auth::check()) {
            // تسجيل خروج المستخدم باستخدام Auth::logout()
            Auth::logout();

            // إرجاع رسالة نجاح في حالة تسجيل الخروج بنجاح
            return response()->json([
                'status' => 'success', // حالة الاستجابة
                'message' => 'Successfully logged out', // رسالة الاستجابة
            ]);
        }

        // إذا لم يكن المستخدم مسجل الدخول، يتم إرجاع رسالة خطأ
        return response()->json([
            'status' => 'error', // حالة الاستجابة تشير إلى وجود خطأ
            'message' => 'No user was logged in', // رسالة الخطأ
        ], 401); // كود الحالة HTTP 401 يشير إلى Unauthorized
    }
}
