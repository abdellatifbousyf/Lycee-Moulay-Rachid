<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // ✅ حماية الداشبورد: فقط للمستخدمين المصادق عليهم
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard (4ayab project).
     * ✅ توجيه ديناميكي حسب دور المستخدم
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();

        // ✅ إذا ماكاينش مستخدم (حالة نادرة)، رجع لـ login
        if (!$user) {
            return redirect()->route('login');
        }

        // ✅ التوجيه حسب الدور (id_role) فـ مشروع 4ayab
        return match($user->id_role) {
            1 => redirect()->route('admin.dashboard'),    // 👑 Admin
            2 => redirect()->route('manager.dashboard'),   // 👨‍💼 Manager/Supervisor
            3 => redirect()->route('prof.dashboard'),      // 👨‍🏫 Professor
            4 => redirect()->route('etudiant.dashboard'),  // 🎓 Student
            default => view('home', compact('user')),      // 🔀 Fallback: عرض صفحة عامة
        };
    }

    /**
     * ✅ (اختياري) عرض صفحة الداشبورد العامة (إذا ما بغيتيش توجيه ديناميكي)
     *
     * @return \Illuminate\View\View
     */
    // public function dashboard(): View
    // {
    //     $user = Auth::user();
    //
    //     // ✅ جلب إحصائيات سريعة حسب الدور (اختياري)
    //     $stats = match($user->id_role) {
    //         1 => [ // Admin stats
    //             'total_students' => \App\Models\Etudiant::count(),
    //             'total_profs' => \App\Models\Enseignant::count(),
    //             'today_absences' => \App\Models\Absence::whereDate('created_at', today())->count(),
    //         ],
    //         3 => [ // Prof stats
    //             'my_seances' => \App\Models\Seance::where('id_ens', $user->enseignant->id ?? 0)->count(),
    //             'pending_absences' => \App\Models\Absence::whereHas('seance', fn($q) =>
    //                 $q->where('id_ens', $user->enseignant->id ?? 0)->where('active', 0)
    //             )->count(),
    //         ],
    //         default => [],
    //     };
    //
    //     return view('home', compact('user', 'stats'));
    // }
}
