<?php

namespace App\Exceptions;

use Throwable; // استخدام Throwable بدلاً من Exception
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException; // استيراد الاستثناء NotFoundHttpException
use App\Http\Controllers\Api\ApiResponseTrait; // تأكد من أن المسار صحيح

class Handler extends ExceptionHandler
{
    use ApiResponseTrait; // استخدام الـ trait

    // ...

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // ...
        });
    }
// بحال عنوان ال  (url) غير صحيح
    public function render($request, Throwable $exception) // استخدام Throwable هنا
    {
        if ($exception instanceof NotFoundHttpException) {
            // استخدام دالة من الـ trait
            return $this->errorResponse('الصفحة غير موجودة', self::HTTP_NOT_FOUND);
        }

        return parent::render($request, $exception);
    }

}
