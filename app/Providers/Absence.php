<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ اختياري: إذا بغيتي الحذف الناعم

class Absence extends Model
{
    // ✅ إذا بغيتي تستخدم الحذف الناعم (Soft Delete)
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'absences';

    /**
     * The attributes that are mass assignable.
     *
     * ✅ الحقول اللي مسموح يتزادو/يتحدثو فيها (Mass Assignment)
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_sea',           // ← معرف الحصة
        'id_etu',           // ← معرف التلميذ
        'etat',             // ← حالة الغياب: 0=غائب، 1=حاضر (أفضل من 'status')
        'justification',    // ← مبرر الغياب
        'remark',           // ← ملاحظة إضافية (اختياري)
        'created_by',       // ← من سجل الغياب (اختياري)
    ];

    /**
     * The attributes that should be cast.
     *
     * ✅ تحويل القيم أوتوماتيكياً لأنواع محددة (ميزة حديثة فـ Laravel)
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'etat' => 'boolean',              // ← 0/1 ← true/false
            'justification' => 'string',       // ← ضمان أن النص كيخرج كـ string
            // ✅ إذا استعمليتي الحذف الناعم:
            // 'deleted_at' => 'datetime',
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
        // 'created_by', // إذا ما بغيتيش تعرضو
    ];

    /**
     * ✅ العلاقة مع الحصة (Seance): غياب واحد كيخص حصة وحدة
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seance(): BelongsTo
    {
        return $this->belongsTo(Seance::class, 'id_sea');
    }

    /**
     * ✅ العلاقة مع التلميذ (Etudiant): غياب واحد كيخص تلميذ واحد
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class, 'id_etu');
    }

    /**
     * ✅ (اختياري) العلاقة مع المستخدم اللي سجل الغياب
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function recordedBy(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'created_by');
    // }

    /**
     * ✅ (اختياري) سكوب للغياب غير المبرر
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnjustified($query)
    {
        return $query->whereNull('justification')
            ->orWhere('justification', '');
    }

    /**
     * ✅ (اختياري) سكوب للغياب حسب الحصة
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $seanceId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForSeance($query, int $seanceId)
    {
        return $query->where('id_sea', $seanceId);
    }

    /**
     * ✅ (اختياري) هل الغياب مبرر؟
     *
     * @return bool
     */
    public function isJustified(): bool
    {
        return !empty($this->justification) && trim($this->justification) !== '';
    }

    /**
     * ✅ (اختياري) هل التلميذ حاضر؟
     *
     * @return bool
     */
    public function isPresent(): bool
    {
        return $this->etat === true || $this->etat === 1;
    }
}
