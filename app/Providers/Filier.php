<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ اختياري: إذا بغيتي الحذف الناعم

class Filiere extends Model
{
    // ✅ إذا بغيتي تستخدم الحذف الناعم (Soft Delete)
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fileres';

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
        'nom_filiere',    // ← اسم الشعبة (مهم)
        'code',           // ← كود مختصر (اختياري)
        'description',    // ← وصف الشعبة (اختياري)
        'id_dep',         // ← الربط مع القسم (Departement)
        'active',         // ← شعبة مفعلة/غير مفعلة
        'duree',          // ← مدة الدراسة (مثلاً: 3 سنوات)
        'created_by',     // ← من أنشأ الشعبة (اختياري)
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
            'duree' => 'integer',
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
        'code',           // ← كود الشعبة فريد
        // 'nom_filiere', // ← إذا ما بغيتيش تكرار اسم الشعبة
    ];

    /**
     * ✅ العلاقة مع التلاميذ (Etudiant): شعبة وحدة فيها عدة تلاميذ
     *
     * ⚠️ ملاحظة: `withTimestamps()` كيستعمل فقط مع `belongsToMany` (Many-to-Many)
     * أما `hasMany` (One-to-Many) ما كيحتاجهاش، لأن التواقيع كيتحفظو فـ جدول `etudiants`
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function etudiants(): HasMany
    {
        // ✅ الصيغة الصحيحة: hasMany(Model::class, foreign_key)
        // افترضنا أن الجدول `etudiants` فيه حقل `id_filiere` كيربط للشعبة
        return $this->hasMany(Etudiant::class, 'id_filiere');
    }

    /**
     * ✅ العلاقة مع القسم (Departement): شعبة وحدة كيخصها قسم واحد
     *
     * ⚠️ تحذير: الكود القديم كان كيستخدم `belongsTo('App\User'...)` ← هذا غلط كبير!
     * الصحيح: `belongsTo(Departement::class, 'id_dep')`
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, 'id_dep');
    }

    /**
     * ✅ العلاقة مع المواد (Matiere): شعبة وحدة فيها عدة مواد
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matieres(): HasMany
    {
        return $this->hasMany(Matiere::class, 'id_filiere');
    }

    /**
     * ✅ (اختياري) العلاقة مع الأساتذة (عبر المواد)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    // public function enseignants(): HasManyThrough
    // {
    //     return $this->hasManyThrough(
    //         Enseignant::class,
    //         Matiere::class,
    //         'id_filiere',  // Foreign key on matieres table
    //         'id',          // Foreign key on enseignants table
    //         'id',          // Local key on filieres table
    //         'id_ens'       // Local key on matieres table
    //     );
    // }

    /**
     * ✅ (اختياري) سكوب لجلب الشعب المفعلة فقط
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
        return $query->where('nom_filiere', 'LIKE', "%{$search}%")
            ->orWhere('code', 'LIKE', "%{$search}%")
            ->orWhereHas('departement', fn($q) => $q->where('nom_dep', 'LIKE', "%{$search}%"));
    }

    /**
     * ✅ (اختياري) سكوب لجلب شعب قسم معين
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $departementId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDepartement($query, int $departementId)
    {
        return $query->where('id_dep', $departementId);
    }

    /**
     * ✅ (اختياري) عدد التلاميذ فـ هاد الشعبة
     *
     * @return int
     */
    public function getEtudiantsCountAttribute(): int
    {
        return $this->etudiants()->count();
    }

    /**
     * ✅ (اختياري) عدد المواد فـ هاد الشعبة
     *
     * @return int
     */
    public function getMatieresCountAttribute(): int
    {
        return $this->matieres()->count();
    }

    /**
     * ✅ (اختياري) اسم الشعبة مع الكود (للعرض)
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->code ? "{$this->nom_filiere} ({$this->code})" : $this->nom_filiere;
    }
}
