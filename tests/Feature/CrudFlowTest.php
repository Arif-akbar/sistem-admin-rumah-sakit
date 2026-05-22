<?php

namespace Tests\Feature;

use App\Models\Agama;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\RekamMedis;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CrudFlowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pasien_baru_bisa_disimpan(): void
    {
        if (! Schema::hasTable('agama') || ! Schema::hasTable('pasien')) {
            $this->markTestSkipped('Tabel SIMRS belum tersedia di database testing.');
        }

        $agama = Agama::firstOrFail();

        $response = $this->post(route('pasien.store'), [
            'nik' => '9999999999999991',
            'nama_pasien' => 'Test Pasien CRUD',
            'tgl_lahir' => '2000-01-01',
            'tempat_lahir' => 'Bandung',
            'jenkel' => 'Laki-Laki',
            'id_agama' => $agama->id,
            'golongan_darah' => 'O',
            'alamat' => 'Jl. Test No. 1',
            'telepon' => '081234567890',
        ]);

        $response->assertRedirect(route('pasien.index'));
        $this->assertDatabaseHas('pasien', [
            'nik' => '9999999999999991',
            'nama_pasien' => 'Test Pasien CRUD',
        ]);
    }

    public function test_rekam_medis_bisa_disimpan(): void
    {
        if (! Schema::hasTable('pasien') || ! Schema::hasTable('dokter') || ! Schema::hasTable('rekam_medis')) {
            $this->markTestSkipped('Tabel SIMRS belum tersedia di database testing.');
        }

        $pasien = Pasien::firstOrFail();
        $dokter = Dokter::firstOrFail();

        $response = $this->post(route('rekam_medis.store'), [
            'no_rm' => $pasien->no_rm,
            'id_dokter' => $dokter->id_dokter,
            'tgl_periksa' => '2026-05-22',
            'jam_periksa' => '10:00',
            'keluhan_utama' => 'Keluhan test',
            'diagnosis' => 'Diagnosis test',
            'kode_icd10' => 'A00',
            'tindakan' => 'Tindakan test',
            'resep' => 'Resep test',
            'tekanan_darah' => '120/80',
            'denyut_nadi' => 72,
            'respirasi' => 20,
            'suhu_tubuh' => 36.5,
            'berat_badan' => 70,
            'tinggi_badan' => 170,
            'catatan' => 'Catatan test',
        ]);

        $response->assertRedirect(route('rekam_medis.index'));
        $this->assertDatabaseHas('rekam_medis', [
            'no_rm' => $pasien->no_rm,
            'diagnosis' => 'Diagnosis test',
            'kode_icd' => 'A00',
        ]);
    }
}
