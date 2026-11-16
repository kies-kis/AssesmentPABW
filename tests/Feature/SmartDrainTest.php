<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Laporan\Models\Laporan;
use Modules\TitikDrainase\Models\TitikDrainase;
use Tests\TestCase;

class SmartDrainTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    private function titik(array $overrides = []): TitikDrainase
    {
        return TitikDrainase::create(array_merge([
            'nama_titik' => 'Saluran Uji',
            'kecamatan' => 'Soreang',
            'deskripsi' => 'Saluran untuk pengujian.',
            'kedalaman_cm' => 80,
        ], $overrides));
    }

    private function laporan(User $user, TitikDrainase $titik, array $overrides = []): Laporan
    {
        return Laporan::create(array_merge([
            'user_id' => $user->id,
            'titik_drainase_id' => $titik->id,
            'tinggi_air_cm' => 10,
            'status_kondisi' => 'Normal',
            'deskripsi' => 'Kondisi saluran normal.',
        ], $overrides));
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_registration_always_creates_warga_even_if_role_is_sent(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Pendaftar Baru',
            'email' => 'baru@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'baru@example.test',
            'role' => 'warga',
        ]);
    }

    public function test_warga_cannot_create_titik_drainase(): void
    {
        $warga = $this->user('warga');

        $this->actingAs($warga)
            ->post(route('titik-drainase.store'), [
                'nama_titik' => 'Saluran Baru',
                'kecamatan' => 'Banjaran',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('titik_drainases', ['nama_titik' => 'Saluran Baru']);
    }

    public function test_admin_can_create_titik_drainase(): void
    {
        $admin = $this->user('admin');

        $this->actingAs($admin)
            ->post(route('titik-drainase.store'), [
                'nama_titik' => 'Saluran Baru',
                'kecamatan' => 'Banjaran',
                'deskripsi' => 'Saluran baru.',
                'kedalaman_cm' => 75,
            ])
            ->assertRedirect(route('titik-drainase.index'));

        $this->assertDatabaseHas('titik_drainases', [
            'nama_titik' => 'Saluran Baru',
            'kecamatan' => 'Banjaran',
        ]);
    }

    public function test_warga_only_sees_own_laporan(): void
    {
        $warga = $this->user('warga');
        $lain = $this->user('warga');
        $titik = $this->titik();

        $this->laporan($warga, $titik, ['deskripsi' => 'Laporan milik sendiri.']);
        $this->laporan($lain, $titik, ['deskripsi' => 'Laporan milik orang lain.']);

        $this->actingAs($warga)
            ->get(route('laporan.index'))
            ->assertOk()
            ->assertSee('Laporan milik sendiri.')
            ->assertDontSee('Laporan milik orang lain.');
    }

    public function test_warga_cannot_update_or_delete_other_report(): void
    {
        $warga = $this->user('warga');
        $lain = $this->user('warga');
        $titik = $this->titik();
        $laporan = $this->laporan($lain, $titik);

        $this->actingAs($warga)
            ->put(route('laporan.update', $laporan), [
                'titik_drainase_id' => $titik->id,
                'tinggi_air_cm' => 99,
                'status_kondisi' => 'Meluap',
                'deskripsi' => 'Diubah tanpa hak.',
            ])
            ->assertForbidden();

        $this->actingAs($warga)
            ->delete(route('laporan.destroy', $laporan))
            ->assertForbidden();

        $this->assertDatabaseHas('laporans', [
            'id' => $laporan->id,
            'tinggi_air_cm' => 10,
        ]);
    }

    public function test_admin_can_delete_any_report(): void
    {
        $admin = $this->user('admin');
        $warga = $this->user('warga');
        $titik = $this->titik();
        $laporan = $this->laporan($warga, $titik);

        $this->actingAs($admin)
            ->deleteJson(route('laporan.destroy', $laporan))
            ->assertOk();

        $this->assertDatabaseMissing('laporans', ['id' => $laporan->id]);
    }

    public function test_titik_with_report_cannot_be_deleted(): void
    {
        $admin = $this->user('admin');
        $warga = $this->user('warga');
        $titik = $this->titik();
        $this->laporan($warga, $titik);

        $this->actingAs($admin)
            ->delete(route('titik-drainase.destroy', $titik))
            ->assertRedirect();

        $this->assertDatabaseHas('titik_drainases', ['id' => $titik->id]);
    }
}
