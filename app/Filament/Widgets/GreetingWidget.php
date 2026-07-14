<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class GreetingWidget extends Widget
{
    protected string $view = 'filament.widgets.greeting-widget';

    protected static ?int $sort = -1;

    protected int|string|array $columnSpan = 'full';

    /**
     * Shown to every authenticated panel user, regardless of role or
     * permissions, so everyone gets a welcome card on the dashboard.
     */
    public static function canView(): bool
    {
        return Filament::auth()?->check() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        /** @var User|null $user */
        $user = Filament::auth()?->user();

        return [
            'name' => $user?->name ?? '',
            'avatarUrl' => $user?->avatar_url,
            'roles' => $user?->getRoleNames()->all() ?? [],
            'greeting' => $this->timeGreeting(),
            'date' => $this->formattedDate(),
        ];
    }

    private function timeGreeting(): string
    {
        return match (true) {
            Carbon::now()->hour < 11 => 'Selamat pagi',
            Carbon::now()->hour < 15 => 'Selamat siang',
            Carbon::now()->hour < 18 => 'Selamat sore',
            default => 'Selamat malam',
        };
    }

    private function formattedDate(): string
    {
        $now = Carbon::now();

        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $days[$now->dayOfWeek].', '.$now->day.' '.$months[$now->month].' '.$now->year;
    }
}
