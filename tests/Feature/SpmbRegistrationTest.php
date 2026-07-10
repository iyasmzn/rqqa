<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\AdmissionPath;
use App\Models\RegistrationWave;
use App\Models\Setting;
use App\Models\SpmbRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpmbRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private AcademicYear $year;

    private RegistrationWave $openWave;

    private AdmissionPath $path;

    protected function setUp(): void
    {
        parent::setUp();

        $this->year = AcademicYear::factory()->active()->create();
        $this->openWave = RegistrationWave::factory()->open()->create(['academic_year_id' => $this->year->id]);
        $this->path = AdmissionPath::firstOrCreate(
            ['slug' => 'zonasi'],
            ['name' => 'Zonasi', 'is_active' => true],
        );
        Setting::set('spmb_form_enabled', '1');
    }

    public function test_ppdb_page_is_accessible(): void
    {
        $response = $this->get(route('ppdb.index'));

        $response->assertStatus(200);
        $response->assertSee('PPDB');
    }

    public function test_registration_form_submission_creates_record_and_assigns_active_wave(): void
    {
        $response = $this->post(route('ppdb.store'), [
            'full_name' => 'Budi Santoso',
            'nik' => '3273010101080001',
            'phone' => '081234567890',
            'email' => 'budi@example.com',
            'previous_school' => 'SMP Negeri 1 Bandung',
            'admission_path_id' => $this->path->id,
        ]);

        $response->assertRedirect(route('ppdb.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('spmb_registrations', [
            'full_name' => 'Budi Santoso',
            'nik' => '3273010101080001',
            'phone' => '081234567890',
            'admission_path_id' => $this->path->id,
            'academic_year_id' => $this->year->id,
            'registration_wave_id' => $this->openWave->id,
            'status' => 'pending',
        ]);
    }

    public function test_registration_fails_when_no_wave_is_open(): void
    {
        $this->openWave->update([
            'start_date' => now()->subMonths(2),
            'end_date' => now()->subMonth(),
        ]);

        $response = $this->post(route('ppdb.store'), [
            'full_name' => 'Ani Lestari',
            'phone' => '089876543210',
            'previous_school' => 'SMP Swasta',
            'admission_path_id' => $this->path->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('spmb_registrations', ['full_name' => 'Ani Lestari']);
    }

    public function test_registration_fails_when_form_is_disabled(): void
    {
        Setting::set('spmb_form_enabled', '0');

        $response = $this->post(route('ppdb.store'), [
            'full_name' => 'Dewi Rahayu',
            'phone' => '08111111111',
            'previous_school' => 'SMP Negeri 2',
            'admission_path_id' => $this->path->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('spmb_registrations', ['full_name' => 'Dewi Rahayu']);
    }

    public function test_registration_validates_required_fields(): void
    {
        $response = $this->post(route('ppdb.store'), []);

        $response->assertSessionHasErrors(['full_name', 'nik', 'phone', 'previous_school', 'admission_path_id']);
    }

    public function test_registration_rejects_duplicate_nik(): void
    {
        SpmbRegistration::factory()->create(['nik' => '3273010101080001']);

        $response = $this->post(route('ppdb.store'), [
            'full_name' => 'Calon Kedua',
            'nik' => '3273010101080001',
            'phone' => '081234567890',
            'previous_school' => 'SMP Negeri 3',
            'admission_path_id' => $this->path->id,
        ]);

        $response->assertSessionHasErrors(['nik']);
        $this->assertDatabaseMissing('spmb_registrations', ['full_name' => 'Calon Kedua']);
    }

    public function test_registration_rejects_invalid_nik_length(): void
    {
        $response = $this->post(route('ppdb.store'), [
            'full_name' => 'NIK Pendek',
            'nik' => '12345',
            'phone' => '081234567890',
            'previous_school' => 'SMP Negeri 4',
            'admission_path_id' => $this->path->id,
        ]);

        $response->assertSessionHasErrors(['nik']);
    }

    public function test_registration_rejects_inactive_admission_path(): void
    {
        $inactive = AdmissionPath::factory()->create(['is_active' => false]);

        $response = $this->post(route('ppdb.store'), [
            'full_name' => 'Test User',
            'nik' => '3273010101080002',
            'phone' => '081234567890',
            'previous_school' => 'SMP Test',
            'admission_path_id' => $inactive->id,
        ]);

        $response->assertSessionHasErrors(['admission_path_id']);
    }

    public function test_is_open_reflects_wave_dates_and_form_toggle(): void
    {
        $this->assertTrue(SpmbRegistration::isOpen());

        Setting::set('spmb_form_enabled', '0');
        $this->assertFalse(SpmbRegistration::isOpen());

        Setting::set('spmb_form_enabled', '1');
        $this->openWave->update(['is_active' => false]);
        $this->assertFalse(SpmbRegistration::isOpen());
    }

    public function test_only_one_academic_year_stays_active(): void
    {
        $newest = AcademicYear::factory()->active()->create();

        $this->assertTrue($newest->fresh()->is_active);
        $this->assertFalse($this->year->fresh()->is_active);
        $this->assertSame($newest->id, AcademicYear::active()?->id);
    }

    public function test_current_open_only_considers_active_year(): void
    {
        $this->year->update(['is_active' => false]);

        $this->assertNull(RegistrationWave::currentOpen());
    }
}
