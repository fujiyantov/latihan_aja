<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Position;
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
        // \App\Models\User::factory(10)->create();


        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@app.com',
                'password' => 'password',
                'role_id' => 1
            ],
            [
                'name' => 'Lembaga',
                'email' => 'lembaga@app.com',
                'password' => 'password',
                'role_id' => 2
            ],
            [
                'name' => 'Sekretariat',
                'email' => 'sekretariat@app.com',
                'password' => 'password',
                'role_id' => 3
            ],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => bcrypt($user['password']),
                'role_id' => $user['role_id'],
            ]);
        }


        Member::create([
            'name' => 'Faisal',
            'email' => 'email@kampus.com',
            'position_id' => '1',
        ]);


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
