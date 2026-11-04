<?php

namespace Tests\Feature\Filament;

use App\Filament\Pages\FeatureSettings;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class FeatureSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::firstOrCreate(['name' => 'View:FeatureSettings', 'guard_name' => 'web']);

        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web'])
            ->syncPermissions(Permission::all());

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_ebook_feature_can_be_switched_off(): void
    {
        $admin = User::factory()->create()->assignRole('super_admin');

        Livewire::actingAs($admin)
            ->test(FeatureSettings::class)
            ->assertSet('data.feature_ebook', true)
            ->fillForm(['feature_ebook' => false])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified('Pengaturan fitur berhasil disimpan');

        $this->assertFalse(feature_enabled('ebook'));
        $this->assertSame('1 nonaktif', FeatureSettings::getNavigationBadge());
    }
}
