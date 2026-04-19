<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departement extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'departements';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom_dep',
        'description',    // ✅ اختياري: وصف الشعبة
        'code',           // ✅ اختياري: كود مختصر
        'active',         // ✅ اختياري: شعبة مفعلة/غير مفعلة
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            // 'created_at' => 'datetime',
            // 'updated_at' => 'datetime',
        ];
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'created_at',
        // 'updated_at',
    ];

    /**
     * ✅ العلاقة مع الشعب (Filiere): قسم واحد كيحتوي عدة شعب
     *
     * ⚠️ ملاحظة: `withTimestamps()` كيستعمل فقط مع `belongsToMany` (Many-to-Many)
     * أما `hasMany` (One-to-Many) ما كيحتاجهاش، لأن التواقيع كيتحفظو فـ جدول `fileres`
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function filieres(): HasMany
    {
        // ✅ الصيغة الصحيحة: hasMany(Model::class, foreign_key)
        // افترضنا أن الجدول `fileres` فيه حقل `id_dep` كيربط للقسم
        return $this->hasMany(Filiere::class, 'id_dep');
    }

    /**
     * ✅ (اختياري) سكوب لجلب الأقسام المفعلة فقط
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * ✅ (اختياري) سكوب للبحث بالاسم
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('nom_dep', 'LIKE', "%{$search}%");
    }

    /**
     * ✅ (اختياري) عدد الشعب فـ هاد القسم
     *
     * @return int
     */
    public function getFilieresCountAttribute(): int
    {
        return $this->filieres()->count();
    }
}
