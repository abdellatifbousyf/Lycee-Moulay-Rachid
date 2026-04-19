<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ اختياري: إذا بغيتي الحذف الناعم

class Etudiant extends Model
{
    // ✅ إذا بغيتي تستخدم الحذف الناعم (Soft Delete)
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'etudiants';

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
        'cne',              // ← الرقم الوطني (مهم: فريد)
        'nom_etu',          // ← اسم العائلة
        'prenom_etu',       // ← الاسم الشخصي
        'phone_etu',        // ← رقم الهاتف
        'adresse',          // ← العنوان (اختياري)
        'date_naissance',   // ← تاريخ الازدياد (اختياري)
        'id_filiere',       // ← الربط مع الشعبة
        'id_user',          // ← الربط مع حساب المستخدم
        'active',           // ← تلميذ مفعل/غير مفعل
        'created_by',       // ← من أنشأ الحساب (اختياري)
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
            'phone_etu' => 'string',
            'date_naissance' => 'date',  // ← تحويل لـ Carbon instance
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
        'cne',      // ← CNE فريد لكل تلميذ
        'id_user',  // ← كل مستخدم كيخص تلميذ واحد
    ];

    /**
     * ✅ العلاقة مع المستخدم (User): تلميذ واحد كيخص حساب مستخدم واحد
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * ✅ العلاقة مع الشعبة (Filiere): تلميذ واحد كيخص شعبة وحدة
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'id_filiere');
    }

    /**
     * ✅ العلاقة مع الغيابات (Absence): تلميذ واحد عندو عدة غيابات
     *
     * ⚠️ ملاحظة: `withTimestamps()` كيستعمل فقط مع `belongsToMany` (Many-to-Many)
     * أما `hasMany` (One-to-Many) ما كيحتاجهاش، لأن التواقيع كيتحفظو فـ جدول `absences`
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function absences(): HasMany
    {
        // ✅ الصيغة الصحيحة: hasMany(Model::class, foreign_key)
        // افترضنا أن الجدول `absences` فيه حقل `id_etu` كيربط للتلميذ
        return $this->hasMany(Absence::class, 'id_etu');
    }

    /**
     * ✅ (اختياري) العلاقة مع الحصص (عبر الغيابات)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    // public function seances(): HasManyThrough
    // {
    //     return $this->hasManyThrough(
    //         Seance::class,
    //         Absence::class,
    //         'id_etu',      // Foreign key on absences table
    //         'id',          // Foreign key on seances table
    //         'id',          // Local key on etudiants table
    //         'id_sea'       // Local key on absences table
    //     );
    // }

    /**
     * ✅ (اختياري) سكوب لجلب التلاميذ المفعلة فقط
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * ✅ (اختياري) سكوب للبحث بالاسم أو الـ CNE
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('nom_etu', 'LIKE', "%{$search}%")
            ->orWhere('prenom_etu', 'LIKE', "%{$search}%")
            ->orWhere('cne', 'LIKE', "%{$search}%")
            ->orWhereHas('user', fn($q) => $q->where('email', 'LIKE', "%{$search}%"));
    }

    /**
     * ✅ (اختياري) سكوب لجلب تلاميذ شعبة معينة
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $filiereId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByFiliere($query, int $filiereId)
    {
        return $query->where('id_filiere', $filiereId);
    }

    /**
     * ✅ (اختياري) الاسم الكامل للتلميذ
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->prenom_etu} {$this->nom_etu}");
    }

    /**
     * ✅ (اختياري) عدد الغيابات الإجمالية
     *
     * @return int
     */
    public function getAbsencesCountAttribute(): int
    {
        return $this->absences()->count();
    }

    /**
     * ✅ (اختياري) عدد الغيابات غير المبررة
     *
     * @return int
     */
    public function getUnjustifiedAbsencesCountAttribute(): int
    {
        return $this->absences()
            ->where(function($q) {
                $q->whereNull('justification')
                  ->orWhere('justification', '');
            })
            ->where('etat', false) // غائب فقط
            ->count();
    }

    /**
     * ✅ (اختياري) نسبة الحضور (%)
     *
     * @return float
     */
    public function getAttendanceRateAttribute(): float
    {
        $total = $this->absences()->count();
        if ($total === 0) return 100.0;

        $present = $this->absences()->where('etat', true)->count();
        return round(($present / $total) * 100, 2);
    }

    /**
     * ✅ (اختياري) هل التلميذ عندو غيابات غير مبررة؟
     *
     * @return bool
     */
    public function hasUnjustifiedAbsences(): bool
    {
        return $this->absences()
            ->where('etat', false)
            ->where(function($q) {
                $q->whereNull('justification')
                  ->orWhere('justification', '');
            })
            ->exists();
    }
}
