<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ اختياري: إذا بغيتي الحذف الناعم

class Matiere extends Model
{
    // ✅ إذا بغيتي تستخدم الحذف الناعم (Soft Delete)
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'matieres';

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
        'nom_mat',        // ← اسم المادة (مهم)
        'code',           // ← كود مختصر (اختياري)
        'description',    // ← وصف المادة (اختياري)
        'coeff',          // ← معامل المادة (اختياري)
        'id_filiere',     // ← الربط مع الشعبة
        'id_sem',         // ← الربط مع الفصل الدراسي
        'id_ens',         // ← الربط مع الأستاذ
        'active',         // ← مادة مفعلة/غير مفعلة
        'created_by',     // ← من أنشأ المادة (اختياري)
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
            'coeff' => 'float',
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
        // 'code',           // ← إذا بغيتي كود فريد لكل مادة
        // ['nom_mat', 'id_filiere'], // ← مادة واحدة فـ شعبة وحدة
    ];

    /**
     * ✅ العلاقة مع الشعبة (Filiere): مادة وحدة كيخصها شعبة وحدة
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'id_filiere');
    }

    /**
     * ✅ العلاقة مع الفصل الدراسي (Semestre): مادة وحدة كيخصها فصل واحد
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class, 'id_sem');
    }

    /**
     * ✅ العلاقة مع الأستاذ (Enseignant): مادة وحدة كيشرحها أستاذ واحد
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(Enseignant::class, 'id_ens');
    }

    /**
     * ✅ العلاقة مع الحصص (Seance): مادة وحدة فيها عدة حصص
     *
     * ⚠️ ملاحظة: `withTimestamps()` كيستعمل فقط مع `belongsToMany` (Many-to-Many)
     * أما `hasMany` (One-to-Many) ما كيحتاجهاش، لأن التواقيع كيتحفظو فـ جدول `seances`
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seances(): HasMany
    {
        // ✅ الصيغة الصحيحة: hasMany(Model::class, foreign_key)
        // افترضنا أن الجدول `seances` فيه حقل `id_mat` كيربط للمادة
        return $this->hasMany(Seance::class, 'id_mat');
    }

    /**
     * ✅ (اختياري) العلاقة مع الغيابات (عبر الحصص)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    // public function absences(): HasManyThrough
    // {
    //     return $this->hasManyThrough(
    //         Absence::class,
    //         Seance::class,
    //         'id_mat',      // Foreign key on seances table
    //         'id_sea',      // Foreign key on absences table
    //         'id',          // Local key on matieres table
    //         'id_sea'       // Local key on absences table
    //     );
    // }

    /**
     * ✅ (اختياري) سكوب لجلب المواد المفعلة فقط
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
        return $query->where('nom_mat', 'LIKE', "%{$search}%")
            ->orWhere('code', 'LIKE', "%{$search}%")
            ->orWhereHas('enseignant', fn($q) =>
                $q->where('nom_ens', 'LIKE', "%{$search}%")
                  ->orWhere('prenom_ens', 'LIKE', "%{$search}%")
            );
    }

    /**
     * ✅ (اختياري) سكوب لجلب مواد شعبة معينة
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
     * ✅ (اختياري) سكوب لجلب مواد فصل معين
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $semestreId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySemestre($query, int $semestreId)
    {
        return $query->where('id_sem', $semestreId);
    }

    /**
     * ✅ (اختياري) سكوب لجلب مواد أستاذ معين
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $enseignantId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByEnseignant($query, int $enseignantId)
    {
        return $query->where('id_ens', $enseignantId);
    }

    /**
     * ✅ (اختياري) اسم المادة مع الكود (للعرض)
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->code ? "{$this->nom_mat} ({$this->code})" : $this->nom_mat;
    }

    /**
     * ✅ (اختياري) عدد الحصص ديال هاد المادة
     *
     * @return int
     */
    public function getSeancesCountAttribute(): int
    {
        return $this->seances()->count();
    }

    /**
     * ✅ (اختياري) هل المادة عندها حصص مسجلة؟
     *
     * @return bool
     */
    public function hasSeances(): bool
    {
        return $this->seances()->exists();
    }
}
