<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ اختياري: إذا بغيتي الحذف الناعم

class Semestre extends Model
{
    // ✅ إذا بغيتي تستخدم الحذف الناعم (Soft Delete)
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'semestres';

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
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom_sem',        // ← اسم الفصل (مثلاً: 'الفصل الأول', 'S1')
        'code',           // ← كود مختصر (مثلاً: 'S1', 'S2')
        'description',    // ← وصف الفصل (اختياري)
        'order',          // ← ترتيب الفصل (1, 2, 3...)
        'start_date',     // ← تاريخ البداية (اختياري)
        'end_date',       // ← تاريخ النهاية (اختياري)
        'active',         // ← فصل مفعل/غير مفعل
        'created_by',     // ← من أنشأ الفصل (اختياري)
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
            'order' => 'integer',
            'active' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
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
        'code',           // ← كود الفصل فريد (مثلاً: 'S1')
        // ['nom_sem', 'order'], // ← إذا بغيتي منع تكرار الاسم فـ نفس الترتيب
    ];

    /**
     * ✅ العلاقة مع المواد (Matiere): فصل واحد فيه عدة مواد
     *
     * ⚠️ ملاحظة: `withTimestamps()` كيستعمل فقط مع `belongsToMany` (Many-to-Many)
     * أما `hasMany` (One-to-Many) ما كيحتاجهاش، لأن التواقيع كيتحفظو فـ جدول `matieres`
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matieres(): HasMany
    {
        // ✅ الصيغة الصحيحة: hasMany(Model::class, foreign_key)
        // افترضنا أن الجدول `matieres` فيه حقل `id_sem` كيربط للفصل
        return $this->hasMany(Matiere::class, 'id_sem');
    }

    /**
     * ✅ (اختياري) سكوب لجلب الفصول المفعلة فقط
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * ✅ (اختياري) سكوب للبحث بالاسم أو الكود
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('nom_sem', 'LIKE', "%{$search}%")
            ->orWhere('code', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%");
    }

    /**
     * ✅ (اختياري) سكوب لجلب الفصول مرتبة حسب الترتيب
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('nom_sem');
    }

    /**
     * ✅ (اختياري) سكوب لجلب الفصل الحالي (بناءً على التاريخ)
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrent($query)
    {
        return $query->where('active', true)
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today());
    }

    /**
     * ✅ (اختياري) عدد المواد فـ هاد الفصل
     *
     * @return int
     */
    public function getMatieresCountAttribute(): int
    {
        return $this->matieres()->count();
    }

    /**
     * ✅ (اختياري) اسم الفصل مع الكود (للعرض)
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->code ? "{$this->nom_sem} ({$this->code})" : $this->nom_sem;
    }

    /**
     * ✅ (اختياري) هل الفصل جاري حالياً؟
     *
     * @return bool
     */
    public function getIsCurrentAttribute(): bool
    {
        if (!$this->start_date || !$this->end_date) return false;

        return today()->between($this->start_date, $this->end_date);
    }

    /**
     * ✅ (اختياري) مدة الفصل بالأيام
     *
     * @return int|null
     */
    public function getDurationDaysAttribute(): ?int
    {
        if (!$this->start_date || !$this->end_date) return null;

        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * ✅ (اختياري) قواعد التحقق الموحدة
     *
     * @param  int|null  $id
     * @return array<string, string>
     */
    public static function rules(?int $id = null): array
    {
        $uniqueCode = $id ? "unique:semestres,code,{$id}" : 'unique:semestres,code';

        return [
            'nom_sem' => 'required|string|max:255',
            'code' => "required|string|max:20|{$uniqueCode}",
            'description' => 'nullable|string|max:500',
            'order' => 'required|integer|min:1|max:20',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'active' => 'boolean',
        ];
    }
}
