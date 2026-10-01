<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AntiNegativeContentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Uji coba jika admin memasukkan kata-kata terlarang (judol/porno).
     */
    public function test_admin_is_suspended_when_submitting_banned_words(): void
    {
        // 1. Siapkan data Admin nakal
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'name' => 'Admin Nakal',
        ]);

        // 2. Login sebagai admin
        $this->actingAs($admin);

        // 3. Admin mencoba membuat sanggar baru dengan menyelipkan kata "slot gacor" di deskripsi
        $response = $this->post('/admin/sanggars', [
            'nama_sanggar' => 'Sanggar Tarian Indah',
            'kategori' => 'Tari',
            'kabupaten' => 'Aceh Tengah',
            'tahun_berdiri' => 2020,
            'deskripsi' => 'Sanggar ini sangat bagus, ayo main slot gacor maxwin di sini!',
            'kontak' => '08123456789',
        ]);

        // 4. Pastikan response redirect ke login dan ada pesan error
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email' => 'Akun Anda telah DITANGGUHKAN karena mencoba mengunggah konten terlarang.']);

        // 5. Cek di database apakah status admin berubah menjadi 'suspended'
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'status' => 'suspended'
        ]);

        // 6. Cek apakah ada log CRITICAL yang tercatat di activity_logs
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'activity' => '[CRITICAL] Auto-Suspend: Terdeteksi mengunggah konten terlarang.',
        ]);

        // 7. Pastikan data sanggar yang mengandung judol TIDAK tersimpan
        $this->assertDatabaseMissing('sanggars', [
            'nama_sanggar' => 'Sanggar Tarian Indah'
        ]);
        
        echo "\n[SKENARIO BERHASIL] Admin Nakal mencoba input kata 'slot gacor maxwin'.\n";
        echo "-> Sistem menolak inputan ke database.\n";
        echo "-> Akun Admin langsung diubah menjadi 'suspended'.\n";
        echo "-> Admin di-logout paksa dan dilempar ke halaman Login.\n";
        echo "-> Log aktivitas tercatat merah ke Dashboard Master Admin.\n";
    }
}
