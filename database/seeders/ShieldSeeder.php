<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Flush cache sebelum generate agar state bersih
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 2. Generate semua permissions (resources, pages, widgets)
        Artisan::call('shield:generate', [
            '--all' => true,
            '--panel' => 'admin',
            '--ignore-existing-policies' => true,
            '--no-interaction' => true,
        ]);

        // 3. Flush cache lagi agar Permission::all() baca dari DB bukan cache lama
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 4. Buat role super_admin dan beri semua permissions
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web']
        );

        $superAdmin->syncPermissions(Permission::all());

        // 5. Buat role panel_user (tanpa permissions)
        Role::firstOrCreate(
            ['name' => 'panel_user', 'guard_name' => 'web']
        );

        // 6. Assign super_admin ke user admin (fallback ke user pertama jika email tidak ditemukan)
        $admin = User::where('email', 'admin@email.com')->first()
            ?? User::first();

        $admin?->assignRole('super_admin');
    }
}
