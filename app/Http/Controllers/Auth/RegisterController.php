<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     * ✅ Laravel 13: استخدم مسار مباشر بدلاً من RouteServiceProvider::HOME
     *
     * @var string
     */
    protected string $redirectTo = '/dashboard';
    // أو حسب مشروع 4ayab: '/login?registered=1' ليعود للأدمن ليضيف المزيد

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // ✅ حماية صفحة التسجيل من المستخدمين المصادق عليهم
        $this->middleware('guest');
    }

    /**
     * ✅ (مهم لـ 4ayab) تجاوز ميثود التسجيل للتعامل مع id_role
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // 1️⃣ التحقق من البيانات
        $this->validator($request->all())->validate();

        // 2️⃣ إنشاء المستخدم عبر دالة create()
        $user = $this->create($request->all());

        // 3️⃣ إطلاق حدث التسجيل (مهم لـ الإشعارات والـ Listeners)
        event(new Registered($user));

        // 4️⃣ تسجيل المحاولة فـ الـ Log (لأغراض الأمان)
        Log::info('4ayab: New user registered - ' . $user->email, [
            'user_id' => $user->id,
            'role' => $user->id_role,
            'ip' => $request->ip(),
        ]);

        // 5️⃣ تسجيل الدخول تلقائياً (اختياري - يمكن إلغاؤه إذا التسجيل بيد الأدمن)
        // $this->guard()->login($user);

        // 6️⃣ التوجيه بعد النجاح
        return redirect($this->redirectPath())
            ->with('success', '✅ تم إنشاء الحساب بنجاح');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array<string, mixed>  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'name'      => ['required', 'string', 'max:255', 'min:3'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            // ✅ حقول إضافية لمشروع 4ayab (إذا كانت مطلوبة فـ فورم التسجيل العام)
            'id_role'   => ['nullable', 'integer', 'in:1,2,3,4'], // 1=Admin, 2=Manager, 3=Prof, 4=Student
            'prenom'    => ['nullable', 'string', 'max:255'],
            'phone'     => ['nullable', 'numeric', 'digits:10'],
        ], [
            // ✅ رسائل خطأ مخصصة بالعربية
            'email.unique' => 'هذا البريد الإلكتروني مستخدم مسبقاً',
            'password.confirmed' => 'كلمتا المرور غير متطابقتين',
            'id_role.in' => 'نوع المستخدم غير صالح',
            'phone.digits' => 'رقم الهاتف يجب أن يتكون من 10 أرقام',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array<string, mixed>  $data
     * @return \App\Models\User
     */
    protected function create(array $data): User
    {
        return User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            // ✅ تعيين الدور افتراضياً (يمكن تعديله حسب الحاجة)
            'id_role'   => $data['id_role'] ?? 4, // 👈 الافتراضي: تلميذ (Student)
            // ✅ حقول إضافية إذا كانت فـ جدول users
            // 'prenom'  => $data['prenom'] ?? null,
            // 'phone'   => $data['phone'] ?? null,
            'created_by'=> auth()->id(), // ✅ تتبع من أنشأ الحساب (إذا كان مسجل)
        ]);
    }

    /**
     * ✅ (اختياري) تحديد حقل التسجيل (إيميل أو اسم مستخدم)
     */
    public function username(): string
    {
        return 'email';
    }

    /**
     * ✅ (اختياري) تخصيص رسالة النجاح بعد التسجيل
     */
    // protected function registered(Request $request, $user): \Illuminate\Http\RedirectResponse|null
    // {
    //     // مثال: إرسال إيميل ترحيبي
    //     // $user->sendWelcomeEmail();
    //
    //     Log::info('4ayab: User ' . $user->email . ' completed registration flow');
    //
    //     return null; // استخدم التوجيه الافتراضي
    // }
}
