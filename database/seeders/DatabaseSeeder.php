<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $roles = ['Admin', 'Himpunan', 'Ortom', 'Komunitas', 'BEM', 'Kaprodi', 'Pembina', 'BKA', 'Sekretariat', 'Dekan', 'Wadek III', 'Wadek II', 'Keuangan'];

        foreach ($roles as $role) {
            $storeRole = new Role();
            $storeRole->name = $role;
            $storeRole->status = 1;
            $storeRole->save();
        }

        $collections = Role::all();
        foreach ($collections as $item) {
            User::create([
                'name' => $item->name,
                'email' => strtolower(str_replace(' ', '_', $item->name)) . '@app.com',
                'password' => bcrypt('password'),
                'role_id' => $item->id,
            ]);
        }

        $positions = [
            "Dekan",
            "Wakil Dekan 1",
            "Wakil Dekan 2",
            "Wakil Dekan 3",
            "Ka. Prodi Teknik Sipil",
            "Ka. Prodi Teknik Elektro",
            "Ka. Prodi Teknik S1 Kimia",
            "Ka. Prodi Teknik Mesin",
            "Ka. Prodi Teknik Industri",
            "Ka. Prodi Arsitektur",
            "Ka. Prodi Teknik Informatika",
            "Ka. Prodi D3 OAB",
            "Ka. Prodi S2 Teknik Kimia",
            "Ka. Lab Fisika",
            "Ka. Lab Bahasa",
            "Ka. Perkuliahan",
            "Ka. Subag Akademik",
            "Ka. Rumah Tangga",
            "Ka. Keuangan",
            "Ka. Tata Usaha",
            "Ka. IT & PUSKOM",
            "Ka. Humas & PMB",
            "Ka. UKM",
            "Pelayanan Terpadu",
            "Ka. LBP",
            "SDM",
            "BKA",
            "Ikalum FT UMJ",
            "Ka. Satpam",
            "Ka. Bag Perpustakaan",
            "Koordinator AIK",
            "Pengadaan",
            "BEM",
            "DPM",
            "IMM",
            "UKM Mapena",
            "Komunitas FT UMJ",
            "HMS",
            "HIMAEL",
            "HIMATEKA",
            "HMM",
            "HMTI",
            "HIMARS",
            "HMIF",
            "HMAB",
        ];

        foreach ($positions as $position) {
            Position::create([
                'name' => $position,
            ]);
        }
    }
}
