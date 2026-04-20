<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ✅ إذا كنت تستعمل الـ API

class User extends Authenticatable implements MustVerifyEmail
{
    // ✅ Traits الأساسية
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * ✅ الحقول اللي مسموح يتزادو/يتحدثو فيها (Mass Assignment)
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_role',          // ← الربط مع جدول الأدوار
        'phone',            // ← رقم الهاتف (اختياري)
        'avatar',           // ← الصورة الشخصية (اختياري)
        'active',           // ← مستخدم مفعل/غير مفعل
        'last_login_at',    // ← آخر دخول (اختياري)
        'last_login_ip',    // ← آي بي آخر دخول (اختياري)
        'created_by',       // ← من أنشأ الحساب (اختياري)
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * ✅ الحقول اللي ما خاصهاش تتعرض فـ الـ JSON/API
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at', // ← اختياري: إذا ما بغيتيش تعرضو
    ];

    /**
     * The attributes that should be cast.
     *
     * ✅ Laravel 9+: استخدم دالة `casts()` بدلاً من الخاصية `$casts`
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',        // ← Laravel 11+: تشفير أوتوماتيكي
            'active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * ✅ العلاقة مع الدور (Role): مستخدم واحد عندو دور واحد
     *
     * ⚠️ ملاحظة: خاصك تحدد الـ foreign key `id_role` لأنه ماشي المعيار الإنجليزي `role_id`
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    /**
     * ✅ العلاقة مع التلميذ (Etudiant): مستخدم واحد ممكن يكون تلميذ
     *
     * ⚠️ ملاحظة: خاصك تحدد الـ foreign key `id_user` لأنه ماشي المعيار الإنجليزي `user_id`
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function etudiant(): HasOne
    {
        return $this->hasOne(Etudiant::class, 'id_user');
    }

    /**
     * ✅ العلاقة مع الأستاذ (Enseignant): مستخدم واحد ممكن يكون أستاذ
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function enseignant(): HasOne
    {
        return $this->hasOne(Enseignant::class, 'id_user');
    }

    /**
     * ✅ (اختياري) دالة مساعدة: واش المستخدم أدمن؟
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->id_role === \App\Models\Role::ADMIN;
    }

    /**
     * ✅ (اختياري) دالة مساعدة: واش المستخدم أستاذ؟
     *
     * @return bool
     */
    public function isProf(): bool
    {
        return $this->id_role === \App\Models\Role::PROF;
    }

    /**
     * ✅ (اختياري) دالة مساعدة: واش المستخدم تلميذ؟
     *
     * @return bool
     */
    public function isStudent(): bool
    {
        return $this->id_role === \App\Models\Role::STUDENT;
    }

    /**
     * ✅ (اختياري) دالة مساعدة: جلب الملف الشخصي (أستاذ أو تلميذ)
     *
     * @return \App\Models\Etudiant|\App\Models\Enseignant|null
     */
    public function getProfileAttribute()
    {
        return $this->etudiant ?? $this->enseignant;
    }

    /**
     * ✅ (اختياري) سكوب لجلب المستخدمين المفعلة فقط
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * ✅ (اختياري) سكوب للبحث بالاسم أو الإيميل
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhereHas('role', fn($q) => $q->where('nom_role', 'LIKE', "%{$search}%"));
    }

    /**
     * ✅ (اختياري) سكوب لجلب مستخدمين بدور معين
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $roleId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRole($query, int $roleId)
    {
        return $query->where('id_role', $roleId);
    }

    /**
     * ✅ (اختياري) الاسم الكامل مع الدور (للعرض)
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        $roleName = $this->role?->nom_role ?? 'مستخدم';
        return "{$this->name} ({$roleName})";
    }

    /**
     * ✅ (اختياري) رابط الصورة الشخصية (مع fallback)
     *
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset("storage/{$this->avatar}");
        }

        // ✅ Gravatar fallback
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
    }

    /**
     * ✅ (اختياري) قواعد التحقق الموحدة
     *
     * @param  int|null  $id
     * @return array<string, string>
     */
    public static function rules(?int $id = null): array
    {
        $uniqueEmail = $id ? "unique:users,email,{$id}" : 'unique:users,email';

        return [
            'name' => 'required|string|max:255',
            'email' => "required|email|max:255|{$uniqueEmail}",
            'password' => $id ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'id_role' => 'required|exists:roles,id',
            'phone' => 'nullable|numeric|digits:10',
            'avatar' => 'nullable|image|max:2048',
            'active' => 'boolean',
        ];
    }

    /**
     * ✅ (اختياري) تسجيل آخر دخول المستخدم
     *
     * @return void
     */
    public function recordLogin(): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }
}
