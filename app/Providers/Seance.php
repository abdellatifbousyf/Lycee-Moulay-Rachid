<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ اختياري: إذا بغيتي الحذف الناعم

class Seance extends Model
{
    // ✅ إذا بغيتي تستخدم الحذف الناعم (Soft Delete)
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seances';

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
        'date',              // ← تاريخ الحصة
        'heure_debut',       // ← وقت البداية
        'heure_fin',         // ← وقت النهاية
        'id_mat',            // ← الربط مع المادة
        'id_ens',            // ← الربط مع الأستاذ
        'type',              // ← نوع الحصة: Cours, TD, TP, Examen
        'salle',             // ← القاعة (اختياري)
        'active',            // ← 0=غير مسجلة، 1=مسجلة (للغياب)
        'remark',            // ← ملاحظة (اختياري)
        'created_by',        // ← من أنشأ الحصة (اختياري)
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
            'date' => 'date',                    // ← تحويل لـ Carbon instance
            'heure_debut' => 'datetime:H:i',     // ← تنسيق الوقت فقط
            'heure_fin' => 'datetime:H:i',
            'active' => 'boolean',
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
        // ['date', 'heure_debut', 'id_ens'], // ← منع تكرار حصة لنفس الأستاذ فـ نفس الوقت
    ];

    /**
     * ✅ العلاقة مع الأستاذ (Enseignant): حصة وحدة كيشرحها أستاذ واحد
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(Enseignant::class, 'id_ens');
    }

    /**
     * ✅ العلاقة مع المادة (Matiere): حصة وحدة كيخصها مادة وحدة
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'id_mat');
    }

    /**
     * ✅ العلاقة مع الغيابات (Absence): حصة وحدة فيها عدة غيابات
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class, 'id_sea');
    }

    /**
     * ✅ (اختياري) العلاقة مع الشعبة (عبر المادة)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    // public function filiere()
    // {
    //     return $this->hasOneThrough(
    //         Filiere::class,
    //         Matiere::class,
    //         'id',           // Foreign key on matieres table
    //         'id',           // Foreign key on filieres table
    //         'id_mat',       // Local key on seances table
    //         'id_filiere'    // Local key on matieres table
    //     );
    // }

    /**
     * ✅ (اختياري) سكوب لجلب الحصص المفعلة فقط (غير مسجلة بعد)
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('active', 0);
    }

    /**
     * ✅ (اختياري) سكوب لجلب الحصص المسجلة (تم تسجيل غياباتها)
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('active', 1);
    }

    /**
     * ✅ (اختياري) سكوب لجلب حصص تاريخ معين
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|\Carbon\Carbon  $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * ✅ (اختياري) سكوب لجلب حصص أستاذ معين
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
     * ✅ (اختياري) سكوب لجلب حصص مادة معينة
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $matiereId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByMatiere($query, int $matiereId)
    {
        return $query->where('id_mat', $matiereId);
    }

    /**
     * ✅ (اختياري) سكوب لجلب حصص اليوم
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    /**
     * ✅ (اختياري) سكوب لجلب حصص هذا الأسبوع
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * ✅ (اختياري) تنسيق التاريخ للعرض
     *
     * @return string
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->date ? $this->date->locale('ar')->isoFormat('LL') : '';
    }

    /**
     * ✅ (اختياري) تنسيق الوقت للعرض
     *
     * @return string
     */
    public function getTimeSlotAttribute(): string
    {
        $start = $this->heure_debut?->format('H:i') ?? '';
        $end = $this->heure_fin?->format('H:i') ?? '';
        return $start && $end ? "{$start} - {$end}" : '';
    }

    /**
     * ✅ (اختياري) مدة الحصة بالدقائق
     *
     * @return int
     */
    public function getDurationMinutesAttribute(): int
    {
        if (!$this->heure_debut || !$this->heure_fin) return 0;

        return $this->heure_debut->diffInMinutes($this->heure_fin);
    }

    /**
     * ✅ (اختياري) هل الحصة جارية الآن؟
     *
     * @return bool
     */
    public function getIsOngoingAttribute(): bool
    {
        if (!$this->date || !$this->heure_debut || !$this->heure_fin) return false;

        $now = now();
        $start = $this->date->copy()->setTimeFromTimeString($this->heure_debut->format('H:i'));
        $end = $this->date->copy()->setTimeFromTimeString($this->heure_fin->format('H:i'));

        return $now->between($start, $end);
    }

    /**
     * ✅ (اختياري) عدد الغيابات فـ هاد الحصة
     *
     * @return int
     */
    public function getAbsencesCountAttribute(): int
    {
        return $this->absences()->count();
    }

    /**
     * ✅ (اختياري) عدد الغياب فـ هاد الحصة
     *
     * @return int
     */
    public function getAbsentCountAttribute(): int
    {
        return $this->absences()->where('etat', false)->count();
    }

    /**
     * ✅ (اختياري) نسبة الغياب فـ هاد الحصة (%)
     *
     * @return float
     */
    public function getAbsenceRateAttribute(): float
    {
        $total = $this->absences()->count();
        if ($total === 0) return 0.0;

        $absent = $this->absences()->where('etat', false)->count();
        return round(($absent / $total) * 100, 2);
    }

    /**
     * ✅ (اختياري) هل يمكن تسجيل غيابات فـ هاد الحصة؟
     *
     * @return bool
     */
    public function canRecordAbsences(): bool
    {
        // ✅ شروط تسجيل الغياب:
        // 1. الحصة غير مسجلة بعد (active=0)
        // 2. التاريخ اليوم أو قبل (ما يمكنش تسجل غياب لحصة فـ المستقبل)
        // 3. الوقت بدا أو داز

        return !$this->active && $this->date->lte(today());
    }

    /**
     * ✅ (اختياري) قواعد التحقق الموحدة
     *
     * @param  int|null  $id
     * @return array<string, string>
     */
    public static function rules(?int $id = null): array
    {
        return [
            'date' => 'required|date|after_or_equal:today',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'id_mat' => 'required|exists:matieres,id',
            'id_ens' => 'required|exists:enseignants,id',
            'type' => 'nullable|string|in:Cours,TD,TP,Examen,Reunion',
            'salle' => 'nullable|string|max:50',
            'active' => 'boolean',
            'remark' => 'nullable|string|max:500',
        ];
    }
}
