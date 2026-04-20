<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ اختياري: إذا بغيتي الحذف الناعم

class Role extends Model
{
    // ✅ إذا بغيتي تستخدم الحذف الناعم (Soft Delete)
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * ✅ الحقول اللي مسموح يتزادو/يتحدثو فيها (Mass Assignment)
     * ⚠️ ملاحظة: `'id'` ما كيحطش فـ $fillable لأنه كيولد أوتوماتيكياً
     * ⚠️ تصحيح: `'tyoe'` ← `'type'` أو `'nom_role'`
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom_role',       // ← اسم الدور (مثلاً: 'أدمن', 'أستاذ', 'تلميذ')
        'type',           // ← نوع الدور (اختياري: 'admin', 'prof', 'student')
        'description',    // ← وصف الدور (اختياري)
        'level',          // ← مستوى الصلاحية (مثلاً: 1=أعلى، 4=أدنى)
        'active',         // ← دور مفعل/غير مفعل
        'created_by',     // ← من أنشأ الدور (اختياري)
    ];

    /**
     * The attributes that should be cast.
     *
     * ✅ تحويل القيم أوتوماتيكياً لأنواع محددة
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'level' => 'integer',
            // 'deleted_at' => 'datetime', // إذا استعمليتي الـ SoftDeletes
        ];
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * ✅ الحقول اللي ما خاصهاش تتعرض فـ الـ JSON/API
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        // 'deleted_at', // إذا استعمليتي الـ SoftDeletes
    ];

    /**
     * The attributes that should be unique.
     *
     * ✅ الحقول اللي خاصها تكون فريدة (للتحقق السريع)
     *
     * @var array<int, string>
     */
    protected $unique = [
        'type',         // ← نوع الدور فريد (مثلاً: 'admin')
        'nom_role',     // ← اسم الدور فريد (مثلاً: 'مدير النظام')
    ];

    /**
     * ✅ العلاقة مع المستخدمين (User): دور واحد عندو عدة مستخدمين
     *
     * ⚠️ ملاحظة: `withTimestamps()` كيستعمل فقط مع `belongsToMany` (Many-to-Many)
     * أما `hasMany` (One-to-Many) ما كيحتاجهاش، لأن التواقيع كيتحفظو فـ جدول `users`
     *
     * ⚠️ تصحيح: `hasMany('App\User','id_user', 'id_role')` ← المعلمات كانت خاطئة!
     * الصحيح: `hasMany(User::class, 'id_role')` حيث `id_role` هو الحقل فـ جدول `users`
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        // ✅ الصيغة الصحيحة: hasMany(Model::class, foreignKey_on_related_table)
        // افترضنا أن الجدول `users` فيه حقل `id_role` كيربط للدور
        return $this->hasMany(User::class, 'id_role');
    }

    /**
     * ✅ (اختياري) سكوب لجلب الأدوار المفعلة فقط
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * ✅ (اختياري) سكوب للبحث بالاسم أو النوع
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('nom_role', 'LIKE', "%{$search}%")
            ->orWhere('type', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%");
    }

    /**
     * ✅ (اختياري) سكوب لجلب دور حسب النوع (string)
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * ✅ (اختياري) سكوب لجلب دور حسب المستوى
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $level
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByLevel($query, int $level)
    {
        return $query->where('level', $level);
    }

    /**
     * ✅ (اختياري) عدد المستخدمين اللي عندهم هاد الدور
     *
     * @return int
     */
    public function getUsersCountAttribute(): int
    {
        return $this->users()->count();
    }

    /**
     * ✅ (اختياري) هل الدور مستخدم حالياً؟
     *
     * @return bool
     */
    public function isInUse(): bool
    {
        return $this->users()->exists();
    }

    /**
     * ✅ (اختياري) التحقق إذا كان الدور هو الأدمن
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->type === 'admin' || $this->level === 1;
    }

    /**
     * ✅ (اختياري) التحقق إذا كان الدور هو أستاذ
     *
     * @return bool
     */
    public function isProf(): bool
    {
        return $this->type === 'prof' || $this->level === 3;
    }

    /**
     * ✅ (اختياري) التحقق إذا كان الدور هو تلميذ
     *
     * @return bool
     */
    public function isStudent(): bool
    {
        return $this->type === 'student' || $this->level === 4;
    }
}
