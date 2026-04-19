<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Base Controller for 4ayab project.
 * All custom controllers should extend this class.
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * ✅ (اختياري) دالة مساعدة للرد بـ JSON بشكل موحد
     * مفيدة لـ API Responses أو Ajax requests
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondJson(
        mixed $data = null,
        string $message = 'Success',
        int $statusCode = 200
    ): \Illuminate\Http\JsonResponse {
        return response()->json([
            'success' => $statusCode >= 200 && $statusCode < 300,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ], $statusCode);
    }

    /**
     * ✅ (اختياري) دالة مساعدة للرد بخطأ موحد
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @param  mixed  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondError(
        string $message = 'Error',
        int $statusCode = 400,
        mixed $errors = null
    ): \Illuminate\Http\JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()->toISOString(),
        ], $statusCode);
    }

    /**
     * ✅ (اختياري) الحصول على معرف الأستاذ الحالي بأمان
     * مفيدة لـ مشروع 4ayab حيث كل أستاذ يشوف غير بياناتو
     *
     * @return int
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    // protected function getCurrentProfId(): int
    // {
    //     $user = auth()->user();
    //
    //     if (!$user || $user->id_role !== 3) { // 3 = Professor
    //         throw new \Illuminate\Auth\Access\AuthorizationException('غير مصرح كأستاذ');
    //     }
    //
    //     $prof = \App\Models\Enseignant::where('id_user', $user->id)->value('id');
    //
    //     if (!$prof) {
    //         throw new \Illuminate\Auth\Access\AuthorizationException('ملف الأستاذ غير موجود');
    //     }
    //
    //     return $prof;
    // }
}
