<?php

namespace App\Models;

use Database\Factories\SpmbRegistrationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpmbRegistration extends Model
{
    /** @use HasFactory<SpmbRegistrationFactory> */
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'registration_wave_id',
        'admission_path_id',
        'full_name',
        'nik',
        'email',
        'phone',
        'birth_date',
        'birth_place',
        'previous_school',
        'previous_school_city',
        'address',
        'parent_name',
        'parent_phone',
        'notes',
        'status',
        'verified_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'verified_at' => 'datetime',
    ];

    /** @return BelongsTo<AcademicYear, $this> */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /** @return BelongsTo<RegistrationWave, $this> */
    public function registrationWave(): BelongsTo
    {
        return $this->belongsTo(RegistrationWave::class);
    }

    /** @return BelongsTo<AdmissionPath, $this> */
    public function admissionPath(): BelongsTo
    {
        return $this->belongsTo(AdmissionPath::class);
    }

    /** @return array<string, string> */
    public static function statusOptions(): array
    {
        return [
            'pending' => 'Menunggu',
            'verified' => 'Terverifikasi',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
        ];
    }

    /**
     * Whether the public registration form should accept new submissions:
     * a wave of the active academic year must currently be open, and the
     * admin must not have force-closed the form.
     */
    public static function isOpen(): bool
    {
        if (! (bool) Setting::get('spmb_form_enabled', true)) {
            return false;
        }

        return RegistrationWave::currentOpen() !== null;
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status', 'accepted');
    }
}
