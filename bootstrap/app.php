<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // إذا كنت تستعمل API
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ─────────────────────────────────────
        // 📦 تسجيل الـ Middleware المخصصة
        // ─────────────────────────────────────
        $middleware->alias([
            // 'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // ─────────────────────────────────────
        // 🔕 الحقول اللي ما كيتفلاشيوش فـ حالة الخطأ (بديل $dontFlash)
        // ─────────────────────────────────────
        $middleware->validateCsrfTokens(except: [
            // 'api/*',
        ]);
    })

    // ─────────────────────────────────────
    // 🚨 معالجة الاستثناءات (بديل Handler.php)
    // ─────────────────────────────────────
    ->withExceptions(function (Exceptions $exceptions) {

        // ✅ 1. الحقول اللي ما كيتفلاشيوش (بديل: protected $dontFlash)
        $exceptions->dontFlash([
            'password',
            'password_confirmation',
            'current_password', // إضافي لـ 4ayab
        ]);

        // ✅ 2. الأخطاء اللي ما كيتلوجاش (بديل: protected $dontReport)
        $exceptions->dontReport([
            NotFoundHttpException::class,
            // ModelNotFoundException::class,
        ]);

        // ✅ 3. تخصيص الـ Render للاستثناءات (بديل: render() method)
        $exceptions->render(function (Throwable $e, Request $request) {

            // 🎯 مثال: تخصيص صفحة 404 لمشروع 4ayab
            if ($e instanceof NotFoundHttpException) {
                if ($request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'الصفحة أو المورد غير موجود',
                        'code' => 404
                    ], 404);
                }
                // للواجهة الأمامية: رجع للـ view ديالك
                // return response()->view('errors.404', [], 404);
            }

            // 🎯 مثال: تخصيص خطأ المصادقة (API)
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصادق عليك',
                    'code' => 401
                ], 401);
            }

            // 🎯 مثال: تسجيل خطأ مخصص قبل ما يتعرض
            if ($request->is('absence/*')) {
                \Log::warning('4ayab: Absence module error - ' . $e->getMessage());
            }

            // ⚠️ إذا ما طابق والو، خلي لارافيل يتكلف
            return null;
        });

        // ✅ 4. تخصيص الـ Report (بديل: report() method)
        $exceptions->report(function (Throwable $e) {
            // مثال: إرسال تنبيه لـ Slack أو Email عند خطأ فادح
            if ($e->getCode() >= 500) {
                \Log::critical('🔥 4ayab Critical Error: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'url' => request()->fullUrl(),
                ]);
            }
        });

    })->create();
