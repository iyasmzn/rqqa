<?php

namespace App\Models;

use Database\Factories\RegistrationWaveFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class RegistrationWave extends Model
{
    /** @use HasFactory<RegistrationWaveFactory> */
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'name',
        'start_date',
        'end_date',
        'selection_date',
        'announcement_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'selection_date' => 'date',
        'announcement_date' => 'date',
        'is_active' => 'boolean',
    ];

    /** @return BelongsTo<AcademicYear, $this> */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /** @return HasMany<SpmbRegistration, $this> */
    public function registrations(): HasMany
    {
        return $this->hasMany(SpmbRegistration::class);
    }

    /**
     * Whether today falls within this wave's date range and it is active.
     */
    public function isOpen(?Carbon $on = null): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $on = ($on ?? Carbon::today())->startOfDay();

        return $on->betweenIncluded($this->start_date->startOfDay(), $this->end_date->startOfDay());
    }

    /**
     * The currently open wave of the active academic year, if any.
     */
    public static function currentOpen(): ?self
    {
        $year = AcademicYear::active();

        if ($year === null) {
            return null;
        }

        $today = Carbon::today()->toDateString();

        return static::query()
            ->where('academic_year_id', $year->id)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderBy('start_date')
            ->first();
    }

    /**
     * The most relevant wave to display for the active academic year:
     * the one currently open, otherwise the next upcoming, otherwise the latest.
     */
    public static function relevant(): ?self
    {
        $year = AcademicYear::active();

        if ($year === null) {
            return null;
        }

        $today = Carbon::today()->toDateString();

        $base = static::query()
            ->where('academic_year_id', $year->id)
            ->where('is_active', true);

        return static::currentOpen()
            ?? (clone $base)->whereDate('start_date', '>', $today)->orderBy('start_date')->first()
            ?? (clone $base)->orderByDesc('end_date')->first();
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
