<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;


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

    // public function login(Request $request)
    // {
    //     // التحقق من صحة بيانات الإدخال
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'password' => 'required|string|min:6',
    //     ],
    //     [
    //         'email.required' => 'البريد الإلكتروني مطلوب.',
    //         'email.email' => 'يجب أن يكون البريد الإلكتروني عنوانًا صالحًا.',
    //         'password.required' => 'كلمة المرور مطلوبة.',
    //         'password.string' => 'يجب أن تكون كلمة المرور نصًا.',
    //         'password.min' => 'يجب أن تكون كلمة المرور على الأقل 6 أحرف.',
    //     ]);
    //     // إذا فشل التحقق من الصحة، يُرجع الأخطاء باستخدام الدالة errorResponse
    //     if ($validator->fails()) {
    //         return $this->errorResponse($validator->errors(), 422);
    //     }

    //     try {
    //         // محاولة الحصول على الرمز باستخدام بيانات الاعتماد المُصادق عليها
    //         if (!$token = JWTAuth::attempt($validator->validated())) {
    //             // إذا فشلت المصادقة، يتم إرجاع رسالة خطأ باستخدام الدالة errorResponse
    //             return $this->errorResponse('فشلت المصادقة تأكد من الايميل او كلمة السر', 401);
    //         }  

    //     } catch (JWTException $e) {
    //         // في حالة وجود خطأ أثناء إنشاء الرمز، يتم إرجاع رسالة خطأ باستخدام الدالة errorResponse
    //         return $this->errorResponse('حدث خطأ أثناء انشاء ال token', 500);
    //     }

    //     // إذا تم الحصول على الرمز بنجاح، يتم إرجاعه في استجابة JSON
    //     return $this->successResponse(compact('token') ,200);

    // }




    //----------------------------------------------

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

    //______________________________________________________________________________________


    // public function logout()
    // {
    //     auth()->logout();
    //     return response()->json(['message' => 'User successfully signed out']);
    // }


    //_____________________________________________________________________________________

    // public function refreshToken() {
    //     try {
    //         if (!$token = JWTAuth::getToken()) {
    //             return $this->errorResponse('token_not_provided', 401);
    //         }

    //         $newToken = JWTAuth::refresh($token);

    //         return $this->successResponse(['token' => $newToken]);
    //     } catch (TokenInvalidException $e) {
    //         return $this->errorResponse('token_invalid', 401);
    //     } catch (JWTException $e) {
    //         return $this->errorResponse('could_not_refresh_token', 500);
    //     }
    // }
    // }
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
    public function login(Request $request)
    {
        // التحقق من صحة بيانات الإدخال
        $validator = Validator::make($request->all(), [
                    'email' => 'required|email',
                    'password' => 'required|string|min:6',
                ],);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    
        return response()->json([
            'status' => 'success',
            'token' => $token
        ]);
    }
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
            'role'=> 'user' ,
            'created_at'=>  now(),
        
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
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

}
