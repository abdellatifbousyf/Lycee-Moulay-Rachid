<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// ✅ Middleware الأساسية من لارافيل
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;

// ✅ Exception Classes للتعامل مع الأخطاء
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
//use Throwable;

// ✅ Middleware المخصصة ديال مشروع 4ayab
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureEmailVerifiedAndRole;

return Application::configure(basePath: dirname(__DIR__))

    // ─────────────────────────────────────────────────────────────
    // 🗂️ Routing Configuration
    // ─────────────────────────────────────────────────────────────
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
       // api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    // ─────────────────────────────────────────────────────────────
    // ⚙️ Middleware Configuration (بديل Http/Kernel.php)
    // ─────────────────────────────────────────────────────────────
    ->withMiddleware(function (Middleware $middleware) {

        // ✅ 1. Global Middleware Stack (كيديزو على كل الطلبات)
        $middleware->use([
            TrustProxies::class,                    // ← الثقة فـ الـ Proxies (Cloudflare, Nginx...)
            HandleCors::class,                      // ← دعم CORS للـ API
            PreventRequestsDuringMaintenance::class,// ← وضع الصيانة
            ValidatePostSize::class,                // ← التحقق من حجم الـ POST
            TrimStrings::class,                     // ← تقليم النصوص (إلا اللي فـ $except)
            ConvertEmptyStringsToNull::class,       // ← تحويل "" لـ null
        ]);

        // ✅ 2. Web Middleware Group (للصفحات العادية - الواجهة الأمامية)
        $middleware->web(append: [
            EncryptCookies::class,                  // ← تشفير الكوكيز
            AddQueuedCookiesToResponse::class,      // ← إضافة الكوكيز للـ Response
            StartSession::class,                    // ← بدء الجلسة
            ShareErrorsFromSession::class,          // ← مشاركة أخطاء الـ Validation
            \App\Http\Middleware\VerifyCsrfToken::class, // ← حماية CSRF
            SubstituteBindings::class,              // ← ربط الـ Route Parameters بالـ Models
        ]);

        // ✅ 3. API Middleware Group (للـ API - ما فيهاش جلسات ولا CSRF)
        $middleware->api(prepend: [], append: [
            'throttle:api',                         // ← تحديد عدد الطلبات (60/دقيقة)
            SubstituteBindings::class,              // ← ربط الـ Parameters
        ]);

        // ✅ 4. Middleware Group مخصص: 'admin' (للوحة التحكم)
        $middleware->group('admin', [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            SubstituteBindings::class,
            'auth',                                  // ← مصادقة
            'role:1',                                // ← دور الأدمن فقط
        ]);

        // ✅ 5. Route Middleware Aliases (للاستخدام فـ الـ Routes)
        $middleware->alias([
            // 🔐 Auth Middleware
            'auth' => Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'auth.basic' => AuthenticateWithBasicAuth::class,
            'password.confirm' => RequirePassword::class,
            'verified' => EnsureEmailIsVerified::class,

            // 🔑 Authorization
            'can' => Authorize::class,

            // 🛡️ Security & Routing
            'bindings' => SubstituteBindings::class,
            'cache.headers' => SetCacheHeaders::class,
            'signed' => ValidateSignature::class,
            'throttle' => ThrottleRequests::class,

            // 🎯 مشروع 4ayab: Middleware مخصصة
            'role' => CheckRole::class,                      // ← التحقق من الدور (1,2,3,4)
            'verified.role' => EnsureEmailVerifiedAndRole::class, // ← إيميل مؤكد + دور
        ]);

        // ✅ 6. استثناءات حماية CSRF (بديل $except فـ VerifyCsrfToken)
        $middleware->validateCsrfTokens(except: [
            'api/*',                        // ← API routes ما تحتاجش CSRF
            'webhook/*',                    // ← Webhooks خارجية
            'stripe/*', 'paypal/*',         // ← بوابات الدفع
            'absence/webhook/sync',         // ← مزامنة الغياب (4ayab)
            'api/sync/*',                   // ← مزامنة إضافية
        ]);

        // ✅ 7. استثناءات وضع الصيانة (بديل $except فـ CheckForMaintenanceMode)
        $middleware->preventRequestsDuringMaintenance(except: [
            'api/health',                   // ← Health check للـ Monitoring
            'up',                           // ← صفحة الحالة
            'admin/maintenance-bypass',     // ← دخول الأدمن أثناء الصيانة
            'webhook/*',                    // ← Webhooks خارجية
            'stripe/*', 'paypal/*',         // ← مدفوعات
            'absence/webhook/sync',         // ← مزامنة 4ayab
            'api/sync/*',                   // ← مزامنة إضافية
        ]);

        // ✅ 8. throttle configuration للـ API (60 طلب/دقيقة لكل IP)
        $middleware->throttleApi(60, 1);

    })

    // ─────────────────────────────────────────────────────────────
    // 🚨 Exception Handling Configuration (بديل Exceptions/Handler.php)
    // ─────────────────────────────────────────────────────────────
    ->withExceptions(function (Exceptions $exceptions) {

        // ✅ 1. الحقول اللي ما كيتفلاشيوش فـ حالة الخطأ (بديل: $dontFlash)
        $exceptions->dontFlash([
            'password',
            'password_confirmation',
            'current_password',     // ← لـ تغيير الباسوورد
            'new_password',         // ← إضافي لـ 4ayab
        ]);

        // ✅ 2. الأخطاء اللي ما كيتلوجاش (بديل: $dontReport)
        $exceptions->dontReport([
            NotFoundHttpException::class,           // ← 404 - عادي، ما يحتاجش لوغ
            // ModelNotFoundException::class,        // ⚠️ سجلها إذا بغيتي تتبع الروابط المكسورة
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
            if ($e instanceof AuthorizationException) {

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

                // ✅ (اختياري) إرسال تنبيه لـ Slack / Email / Discord فـ الإنتاج
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
