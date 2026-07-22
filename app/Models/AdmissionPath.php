<?php

namespace App\Models;

use Database\Factories\AdmissionPathFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdmissionPath extends Model
{
    /** @use HasFactory<AdmissionPathFactory> */
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'name',
        'slug',
        'icon',
        'icon_image',
        'description',
        'color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /** @return BelongsTo<Institution, $this> */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /** @return HasMany<SpmbRegistration, $this> */
    public function registrations(): HasMany
    {
        return $this->hasMany(SpmbRegistration::class);
    }

    /**
     * Paths available to a given jenjang: those scoped to it plus shared
     * (null-institution) paths. Passing null returns only shared paths.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForInstitution(Builder $query, ?Institution $institution): Builder
    {
        return $query->where(function (Builder $query) use ($institution): void {
            $query->whereNull('institution_id');

            if ($institution !== null) {
                $query->orWhere('institution_id', $institution->id);
            }
        });
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
