<?php

namespace App\http\Controllers\Api;

trait ApiResponseTrait
{
    
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_UNPROCESSABLE_ENTITY = 422;
    const HTTP_METHOD_ERROR_QUERY = 500;
    const HTTP_CONFLICT = 409;


    public function errorResponse($message, $code)
    {
        $errorDetails = [
            self::HTTP_BAD_REQUEST => [
                'error_type' => 'Bad Request',
                'solution' => 'تحقق من صيغة الطلب.'
            ],
            self::HTTP_UNAUTHORIZED => [
                'error_type' => 'Unauthorized',
                'solution' => 'التحقق من بيانات المصادقة.'
            ],
            self::HTTP_FORBIDDEN => [
                'error_type' => 'Forbidden',
                'solution' => 'تحقق من الصلاحيات.'
            ],
            self::HTTP_NOT_FOUND => [
                'error_type' => 'Not Found',
                'solution' => 'تحقق من العنوان المطلوب.'
            ],
            self::HTTP_METHOD_NOT_ALLOWED => [
                'error_type' => 'Method Not Allowed',
                'solution' => 'استخدم الطريقة الصحيحة للطلب.'
            ],
            self::HTTP_METHOD_ERROR_QUERY => [
                'error_type' => 'wrong query',
                'solution' => 'استعلام خاطىء يرجى إعادة المحاولة مرة أخرى.'
            ],
            // ... تفاصيل الأخطاء الأخرى
            self::HTTP_CONFLICT => [
                'error_type' => 'Conflict',
                'solution' => 'البيانات المُرسلة تتعارض مع البيانات الحالية، تحقق من البيانات وأعد المحاولة.'],
            self::HTTP_UNPROCESSABLE_ENTITY => [
                'error_type' => 'Unprocessable Entity',
                'solution' => 'البيانات المُرسلة لا يمكن معالجتها، تحقق من البيانات وأعد المحاولة.'
            ],
        ];
        // التعامل مع الأخطاء غير المعروفة: إذا لم يتم العثور على رمز الحالة داخل مصفوفة $errorDetails، يتم استخدام قيمة افتراضية تمثل خطأ غير معروف وحل مقترح للاتصال بالدعم الفني
        $response = [
            'status' => 'error',
            'message' => $message,
            'error_details' => $errorDetails[$code] ?? ['error_type' => 'Unknown Error', 'solution' => 'اتصل بالدعم الفني.']
        ];

        return response()->json($response, $code);
    }

    public function successResponse($data = null, $message = null, $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
