<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ اختياري: إذا بغيتي الحذف الناعم

class Enseignant extends Model
{
    // ✅ إذا بغيتي تستخدم الحذف الناعم (Soft Delete)
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'enseignants';

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
        'nom_ens',
        'prenom_ens',
        'phone_ens',
        'adresse_ens',
        'email_pro',      // ✅ اختياري: بريد الأستاذ المهني
        'grade',          // ✅ اختياري: الرتبة الأكاديمية
        'specialite',     // ✅ اختياري: التخصص
        'id_user',        // ✅ الربط مع جدول المستخدمين
        'active',         // ✅ اختياري: أستاذ مفعل/غير مفعل
        'created_by',     // ✅ اختياري: من أنشأ الحساب
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
            'phone_ens' => 'string',
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
     * ✅ العلاقة مع المستخدم (User): أستاذ واحد كيخص حساب مستخدم واحد
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * ✅ العلاقة مع المواد (Matiere): أستاذ واحد كيشرح عدة مواد
     *
     * ⚠️ ملاحظة: `withTimestamps()` كيستعمل فقط مع `belongsToMany` (Many-to-Many)
     * أما `hasMany` (One-to-Many) ما كيحتاجهاش، لأن التواقيع كيتحفظو فـ جدول `matieres`
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matieres(): HasMany
    {
        // ✅ الصيغة الصحيحة: hasMany(Model::class, foreign_key)
        // افترضنا أن الجدول `matieres` فيه حقل `id_ens` كيربط للأستاذ
        return $this->hasMany(Matiere::class, 'id_ens');
    }

    /**
     * ✅ العلاقة مع الحصص (Seance): أستاذ واحد كيعطي عدة حصص
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seances(): HasMany
    {
        return $this->hasMany(Seance::class, 'id_ens');
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
    //         'id_ens',      // Foreign key on seances table
    //         'id_sea',      // Foreign key on absences table
    //         'id',          // Local key on enseignants table
    //         'id'           // Local key on seances table
    //     );
    // }

    /**
     * ✅ (اختياري) سكوب لجلب الأساتذة المفعلة فقط
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * ✅ (اختياري) سكوب للبحث بالاسم أو البريد
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('nom_ens', 'LIKE', "%{$search}%")
            ->orWhere('prenom_ens', 'LIKE', "%{$search}%")
            ->orWhereHas('user', fn($q) => $q->where('email', 'LIKE', "%{$search}%"));
    }

    /**
     * ✅ (اختياري) الاسم الكامل للأستاذ
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->prenom_ens} {$this->nom_ens}");
    }

    /**
     * ✅ (اختياري) عدد المواد اللي كيشرحها هاد الأستاذ
     *
     * @return int
     */
    public function getMatieresCountAttribute(): int
    {
        return $this->matieres()->count();
    }

    /**
     * ✅ (اختياري) عدد الحصص الإجمالية
     *
     * @return int
     */
    public function getSeancesCountAttribute(): int
    {
        return $this->seances()->count();
    }
}
