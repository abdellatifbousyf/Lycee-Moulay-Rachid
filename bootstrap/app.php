<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;

// ✅ Exception Classes للتعامل مع الأخطاء
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',      // ✅ إذا كنت تستعمل API
        commands: __DIR__.'/../routes/console.php', // ✅ للـ Schedule
        health: '/up',                          // ✅ Health check endpoint
    )

    // ─────────────────────────────────────────────────────────────
    // ⚙️ Middleware Configuration
    // ─────────────────────────────────────────────────────────────
    ->withMiddleware(function (Middleware $middleware) {

        // ✅ 1. تسجيل استثناءات وضع الصيانة (بديل $except القديم)
        $middleware->preventRequestsDuringMaintenance(
            except: [
                // 🏥 Health & Monitoring
                'api/health',
                'up',

                // 🔐 Admin Bypass (للدخول أثناء الصيانة)
                'admin/maintenance-bypass',

                // 🔗 External Webhooks (GitHub, Stripe, Payment...)
                'webhook/*',
                'stripe/*',

                // 🎯 مشروع 4ayab: مزامنة الغياب الأوتوماتيكية
                'absence/webhook/sync',
                'api/sync/*',
            ]
        );

        // ✅ 2. تسجيل الـ Middleware المخصصة (Aliases)
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

            // 🎯 مشروع 4ayab: Middleware للأدوار
            'role' => \App\Http\Middleware\CheckRole::class,
            'verified.role' => \App\Http\Middleware\EnsureEmailVerifiedAndRole::class,

            // 🔒 CSRF Protection استثناءات
            // 'csrf' => \App\Http\Middleware\VerifyCsrfToken::class,
        ]);

        // ✅ 3. استثناءات حماية CSRF (بديل $except فـ VerifyCsrfToken)
        $middleware->validateCsrfTokens(except: [
            'api/*',                    // ✅ API routes ما تحتاجش CSRF
            'webhook/*',                // ✅ Webhooks خارجية
            'stripe/*',                 // ✅ مدفوعات Stripe
            'absence/webhook/sync',     // ✅ مزامنة 4ayab
        ]);

        // ✅ 4. تسجيل أي Middleware إضافي فـ الـ Stack
        // $middleware->append(\App\Http\Middleware\CustomLog::class);
        // $middleware->prepend(\App\Http\Middleware\BeforeAuth::class);

    })

    // ─────────────────────────────────────────────────────────────
    // 🚨 Exception Handling Configuration (بديل Handler.php)
    // ─────────────────────────────────────────────────────────────
    ->withExceptions(function (Exceptions $exceptions) {

        // ✅ 1. الحقول اللي ما كيتفلاشيوش فـ حالة الخطأ (بديل: $dontFlash)
        $exceptions->dontFlash([
            'password',
            'password_confirmation',
            'current_password',     // ✅ لـ تغيير الباسوورد
            'new_password',         // ✅ إضافي لـ 4ayab
        ]);

        // ✅ 2. الأخطاء اللي ما كيتلوجاش (بديل: $dontReport)
        $exceptions->dontReport([
            NotFoundHttpException::class,           // 404 - عادي
            // ModelNotFoundException::class,        // ⚠️ سجلها إذا بغيتي تتبع الروابط المكسورة
            // \Illuminate\Validation\ValidationException::class, // ✅ لارافيل كيحيدو أوتوماتيكياً
        ]);

        // ✅ 3. تخصيص الـ Render للاستثناءات (بديل: render() method)
        $exceptions->render(function (Throwable $e, Request $request) {

            // 🎯 أ) تخصيص صفحة 404 لمشروع 4ayab
            if ($e instanceof NotFoundHttpException) {

                // للـ API: رجع JSON
                if ($request->is('api/*') || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'الصفحة أو المورد غير موجود',
                        'code' => 404,
                        'path' => $request->path(),
                    ], 404);
                }

                // للواجهة الأمامية: رجع لـ view مخصص (إذا موجود)
                // return response()->view('errors.404', ['path' => $request->path()], 404);
            }

            // 🎯 ب) تخصيص خطأ المصادقة (401)
            if ($e instanceof AuthenticationException) {

                // للـ API
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'غير مصادق عليك، يرجى تسجيل الدخول',
                        'code' => 401,
                    ], 401);
                }

                // للواجهة الأمامية: رجع لـ login
                return redirect()->guest(route('login'));
            }

            // 🎯 ج) خطأ "غير مصرح" (403)
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'غير مصرح لك بالوصول لهذا المورد',
                        'code' => 403,
                    ], 403);
                }

                return redirect()->back()->with('error', '❌ غير مصرح لك بالدخول لهذه الصفحة');
            }

            // 🎯 د) تسجيل أخطاء مخصصة قبل ما يتعرض (لأغراض المراقبة)
            if ($request->is('absence/*') || $request->is('api/absence/*')) {
                \Log::warning('4ayab: Absence module error', [
                    'error' => $e->getMessage(),
                    'class' => get_class($e),
                    'path' => $request->path(),
                    'ip' => $request->ip(),
                ]);
            }

            // ⚠️ إذا ما طابق والو، خلي لارافيل يتكلف بالافتراضي
            return null;
        });

        // ✅ 4. تخصيص الـ Report (بديل: report() method)
        $exceptions->report(function (Throwable $e) {

            // 🚨 أ) تسجيل الأخطاء الفادحة (500+) مع تفاصيل إضافية
            if ($e->getCode() >= 500 || $e instanceof \Error) {

                \Log::critical('🔥 4ayab Critical Error', [
                    'message' => $e->getMessage(),
                    'class' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => config('app.debug') ? $e->getTraceAsString() : null,
                    'url' => request()->fullUrl(),
                    'ip' => request()->ip(),
                    'user_id' => auth()->id(),
                    'user_agent' => request()->userAgent(),
                ]);

                // ✅ (اختياري) إرسال تنبيه لـ Slack / Email / Discord
                // if (config('app.env') === 'production') {
                //     \Illuminate\Support\Facades\Notification::send(
                //         new \App\Models\User(['email' => config('app.admin_email')]),
                //         new \App\Notifications\CriticalErrorAlert($e)
                //     );
                // }
            }

            // 📊 ب) إحصائيات بسيطة للأخطاء (اختياري)
            // \Log::channel('errors-stats')->info('Error occurred', [
            //     'type' => get_class($e),
            //     'time' => now()->toDateTimeString(),
            // ]);
        });

        // ✅ 5. (اختياري) تخصيص الـ Reportable لـ استثناءات محددة
        // $exceptions->reportable(function (\Illuminate\Validation\ValidationException $e) {
        //     // تتبع محاولات الـ Validation الفاشلة المتكررة (لمنع الـ Abuse)
        // });

    })

    // ✅ إنشاء التطبيق ورجوع الـ Instance
    ->create();
