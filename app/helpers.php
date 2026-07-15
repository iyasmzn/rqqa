<?php

use App\Models\AcademicYear;
use App\Models\RegistrationWave;
use App\Models\Setting;
use Illuminate\Support\Str;

if (! function_exists('icon_url')) {
    /**
     * Resolve a stored icon-image value to a usable URL. Accepts absolute URLs
     * and root-relative paths as-is; otherwise treats it as a path on the public
     * disk. Returns null when empty so callers can fall back to an emoji icon.
     */
    function icon_url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return Str::startsWith($path, ['http://', 'https://', '/'])
            ? $path
            : asset('storage/'.$path);
    }
}

if (! function_exists('setting')) {
    /**
     * Get a setting value from the database, with an optional default.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('setting_bool')) {
    /**
     * Get a setting value interpreted as a boolean. Handles the "1"/"0" strings
     * that Toggle fields persist as well as real booleans and the default.
     */
    function setting_bool(string $key, bool $default = false): bool
    {
        $value = Setting::get($key);

        if ($value === null) {
            return $default;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}

if (! function_exists('spmb_year_label')) {
    /**
     * The active academic year label (e.g. "2026/2027"), falling back to the
     * legacy setting or the current calendar year pair.
     */
    function spmb_year_label(): string
    {
        return AcademicYear::active()?->label
            ?? setting('spmb_year', date('Y').'/'.(date('Y') + 1));
    }
}

if (! function_exists('spmb_current_wave')) {
    /**
     * The registration wave that is currently open for the active academic year.
     */
    function spmb_current_wave(): ?RegistrationWave
    {
        return RegistrationWave::currentOpen();
    }
}

if (! function_exists('spmb_in_admission_period')) {
    /**
     * Whether today falls within an active wave of the active academic year.
     */
    function spmb_in_admission_period(): bool
    {
        return spmb_current_wave() !== null;
    }
}
