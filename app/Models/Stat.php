<?php

namespace App\Models;

use Database\Factories\StatFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    /** @use HasFactory<StatFactory> */
    use HasFactory;

    protected $fillable = ['icon', 'icon_image', 'label', 'value', 'sub', 'url', 'open_in_new_tab', 'sort_order'];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'open_in_new_tab' => 'boolean',
    ];

    /**
     * @return Builder<static>
     */
    public static function ordered(): Builder
    {
        return static::orderBy('sort_order')->orderBy('id');
    }
}
