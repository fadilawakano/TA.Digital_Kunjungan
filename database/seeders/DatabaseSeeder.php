<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\BidangStudiUser;
use App\Models\BidangStudi;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat bidang studi
        $biologi  = BidangStudi::firstOrCreate(['nama' => 'Biologi']);
        $kimia    = BidangStudi::firstOrCreate(['nama' => 'Kimia']);
        $fisika   = BidangStudi::firstOrCreate(['nama' => 'Fisika']);
        $lainnya  = BidangStudi::firstOrCreate(['nama' => 'Lainnya']);

        // Petugas Lab
        User::updateOrCreate(
            ['username' => 'fisika123'],
            [
                'name' => 'Petugas Fisika',
                'password' => Hash::make('fisika123'),
                'role' => 'fisika',
            ]
        );

        User::updateOrCreate(
            ['username' => 'biologi123'],
            [
                'name' => 'Petugas Biologi',
                'password' => Hash::make('biologi123'),
                'role' => 'biologi',
            ]
        );

        User::updateOrCreate(
            ['username' => 'kimia123'],
            [
                'name' => 'Petugas Kimia',
                'password' => Hash::make('kimia123'),
                'role' => 'kimia',
            ]
        );

        // Guru campur (Biologi + Kimia)
        $guru = User::updateOrCreate(
            ['username' => '200904H014'],
            [
                'name' => 'Hawa Patty S.Pd',
                'password' => Hash::make('200904H014'),
                'role' => 'guru',
            ]
        );

        BidangStudiUser::insert([
            ['user_id' => $guru->id, 'bidang_studi_id' => $biologi->id],
            ['user_id' => $guru->id, 'bidang_studi_id' => $kimia->id],
            ['user_id' => $guru->id, 'bidang_studi_id' => $fisika->id],
        ]);

        // Guru campur (Biologi + Kimia)
        $guru = User::updateOrCreate(
            ['username' => '199702A003'],
            [
                'name' => 'Ahmad Wakano S.Pd, M.Pd',
                'password' => Hash::make('199702A003'),
                'role' => 'guru',
            ]
        );

        BidangStudiUser::insert([
            ['user_id' => $guru->id, 'bidang_studi_id' => $biologi->id],
            ['user_id' => $guru->id, 'bidang_studi_id' => $kimia->id],
            ['user_id' => $guru->id, 'bidang_studi_id' => $fisika->id],
        ]);

         $guru = User::updateOrCreate(
            ['username' => '200804F001'],
            [
                'name' => 'Faruk Bachmid S.Pd',
                'password' => Hash::make('200804F001'),
                'role' => 'guru',
            ]
        );

         BidangStudiUser::insert([
            ['user_id' => $guru->id, 'bidang_studi_id' => $biologi->id],
            ['user_id' => $guru->id, 'bidang_studi_id' => $kimia->id],
            ['user_id' => $guru->id, 'bidang_studi_id' => $fisika->id],
        ]);

        // Guru Fisika
        $guruFisika = User::updateOrCreate(
            ['username' => 'guru789'],
            [
                'name' => 'Guru Fisika',
                'password' => Hash::make('guru789'),
                'role' => 'guru',
            ]
        );

        BidangStudiUser::insert([
            ['user_id' => $guruFisika->id, 'bidang_studi_id' => $fisika->id],
        ]);

        // Guru Kimia
        $guruKimia = User::updateOrCreate(
            ['username' => '201001S016'],
            [
                'name' => 'Sonny Kakerissa S.Pt',
                'password' => Hash::make('201001S016'),
                'role' => 'guru',
            ]
        );

        BidangStudiUser::insert([
            ['user_id' => $guruKimia->id, 'bidang_studi_id' => $kimia->id],
        ]);

        // Guru Non-Lab
        $guru = User::updateOrCreate(
            ['username' => '200904M004'],
            [
                'name' => 'Mustapa S.Pd',
                'password' => Hash::make('200904M004'),
                'role' => 'guru',
            ]
        );

        BidangStudiUser::insert([
            ['user_id' => $guru->id, 'bidang_studi_id' => $lainnya->id],
        ]);

        $guru = User::updateOrCreate(
            ['username' => '199802S004'],
            [
                'name' => 'Sania Raiba S.Pd, M.Pd',
                'password' => Hash::make('199802S004'),
                'role' => 'guru',
            ]
        );

        BidangStudiUser::insert([
            ['user_id' => $guru->id, 'bidang_studi_id' => $lainnya->id],
        ]);

        // Guru Non-Lab
        $guru = User::updateOrCreate(
            ['username' => '202321K027'],
            [
                'name' => 'Kartini S.Pdi',
                'password' => Hash::make('202321K027'),
                'role' => 'guru',
            ]
        );

        BidangStudiUser::insert([
            ['user_id' => $guru->id, 'bidang_studi_id' => $lainnya->id],
        ]);

        $guru = User::updateOrCreate(
            ['username' => '200604R010'],
            [
                'name' => 'Ridwan Patty S.Ag',
                'password' => Hash::make('200604R010'),
                'role' => 'guru',
            ]
        );

        BidangStudiUser::insert([
            ['user_id' => $guru->id, 'bidang_studi_id' => $lainnya->id],
        ]);

        $guru = User::updateOrCreate(
            ['username' => '200604J007'],
            [
                'name' => 'Jolanda Tibaly S.Pd',
                'password' => Hash::make('200604J007'),
                'role' => 'guru',
            ]
        );

        BidangStudiUser::insert([
            ['user_id' => $guru->id, 'bidang_studi_id' => $lainnya->id],
        ]);

        $guru = User::updateOrCreate(
            ['username' => '200604S007'],
            [
                'name' => 'Jefri Sangadji S.Pd',
                'password' => Hash::make('200604S007'),
                'role' => 'guru',
            ]
        );

        BidangStudiUser::insert([
            ['user_id' => $guru->id, 'bidang_studi_id' => $lainnya->id],
        ]);

        $guru = User::updateOrCreate(
            ['username' => '200804S001'],
            [
                'name' => 'Salami Bachmid S.Pd',
                'password' => Hash::make('200804S001'),
                'role' => 'guru',
            ]
        );

        BidangStudiUser::insert([
            ['user_id' => $guru->id, 'bidang_studi_id' => $lainnya->id],
        ]);

        $guru = User::updateOrCreate(
            ['username' => '200804N004'],
            [
                'name' => 'Nurani Pawati S.Pd',
                'password' => Hash::make('200804N004'),
                'role' => 'guru',
            ]
        );

        BidangStudiUser::insert([
            ['user_id' => $guru->id, 'bidang_studi_id' => $lainnya->id],
        ]);

        $guru = User::updateOrCreate(
            ['username' => '200804R003'],
            [
                'name' => 'Rudolf Picasouw S.Pd',
                'password' => Hash::make('200804R003'),
                'role' => 'guru',
            ]
        );

        BidangStudiUser::insert([
            ['user_id' => $guru->id, 'bidang_studi_id' => $lainnya->id],
        ]);

       

        $muridXI = [
    ['name' => 'Abdul Azam Tamnge', 'username' => '00999F22591'],
    ['name' => 'Alan Alwi Pattimura', 'username' => '00926F71025'],
    ['name' => 'Alfauniza Mussa', 'username' => '00928F93676'],
    ['name' => 'Aulia Zahra Patty', 'username' => '00979F56132'],
    ['name' => 'Dede Yusuf Rahayaan', 'username' => '00952F45750'],
    ['name' => 'Fadila Mussa', 'username' => '00836F90750'],
    ['name' => 'Fazhril Gadri Patty', 'username' => '00942F06593'],
    ['name' => 'Fika Kalsum Wakano', 'username' => '00963F26771'],
    ['name' => 'Gufran A. Bachmid', 'username' => '00752F00545'],
    ['name' => 'Idris Lamani', 'username' => '30962F10103'],
    ['name' => 'Jamalin Litiloly', 'username' => '00831F18613'],
    ['name' => 'Kirana Tomia', 'username' => '00858F20809'],
    ['name' => 'Kurniawati Pattimura', 'username' => '30962F72196'],
    ['name' => 'M. Yusril Samallo', 'username' => '00890F15119'],
    ['name' => 'Mia Pattimura', 'username' => '00810F37581'],
    ['name' => 'Muhamammad Fatur Rahman Patty', 'username' => '00980F57750'],
    ['name' => 'Pahroji Elly', 'username' => '00988F08879'],
    ['name' => 'Ramdani Mussa', 'username' => '00927F32186'],
    ['name' => 'Rouhun Wakano', 'username' => '00914F61721'],
    ['name' => 'Suryanti Kilkussa', 'username' => '00976F67159'],
    ['name' => 'Ulya Riring', 'username' => '00886F62433'],
    ['name' => 'Salsabila Pattimura', 'username' => '00960F96923'],
    ['name' => 'Mutia Laili Mussa', 'username' => '00964F51064'],
];

foreach ($muridXI as $murid) {
    User::updateOrCreate(
        ['username' => $murid['username']],
        [
            'name' => $murid['name'],
            'kelas' => 'XI Fase F1',
            'password' => Hash::make($murid['username']),
            'role' => 'murid',
        ]
    );
}

$muridXIF2 = [
    ['name' => 'Aira Ismianti Tatisina', 'username' => '00983F50250'],
    ['name' => 'Ali Zainal Al Ydrus', 'username' => '00921F56785'],
    ['name' => 'Auria Wakano', 'username' => '00999F48284'],
    ['name' => 'Dani Wakano', 'username' => '30782F88560'],
    ['name' => 'Fadil Fabian', 'username' => '00817F42021'],
    ['name' => 'Farel Jibran Sosal', 'username' => '00928F91944'],
    ['name' => 'Farhat Kakiai', 'username' => '30921F64578'],
    ['name' => 'Ibrahim Mussa', 'username' => '00862F90397'],
    ['name' => 'Kartini Ridwan Kakiyai', 'username' => '00983F48643'],
    ['name' => 'Khamat Ali Keluan', 'username' => '00946F15200'],
    ['name' => 'Mahidar Wakano', 'username' => '00873F23762'],
    ['name' => 'Maeristra Mustia Weno', 'username' => '00944F03881'],
    ['name' => 'Muhammad Ali Fitra Sanaki', 'username' => '00921F63895'],
    ['name' => 'Muhammad Fardhan Tuankotta', 'username' => '00922F37247'],
    ['name' => 'Faisal Riring', 'username' => '00920F86378'],
    ['name' => 'Rahmawati Ukratallo', 'username' => '00870F87486'],
    ['name' => 'Ririn Tomia', 'username' => '00946F22796'],
    ['name' => 'Rozalia Wakano', 'username' => '00996F32100'],
    ['name' => 'Siti Sofia Lahi', 'username' => '00980F26925'],
    ['name' => 'Yusran Pattimura', 'username' => '00911F37794'],
    ['name' => 'Zuliati Pellu', 'username' => '00991F29717'],
    ['name' => 'Aco Reski Tomia', 'username' => '00936F45171'],
    ['name' => 'Abdul Jalil Tunny', 'username' => '00995F66417'],
    ['name' => 'Aidil Afipudin Patty', 'username' => '00856F34266'],
];

foreach ($muridXIF2 as $murid) {
    User::updateOrCreate(
        ['username' => $murid['username']],
        [
            'name' => $murid['name'],
            'kelas' => 'XI Fase F2',
            'password' => Hash::make($murid['username']),
            'role' => 'murid',
        ]
    );
}

$muridXIF3 = [
    ['name' => 'Abdul Karim Parihua', 'username' => '00966F11548'],
    ['name' => 'Ahmad Sani Loilatu', 'username' => '00825F06504'],
    ['name' => 'Arjuna Samallo', 'username' => '30912F89568'],
    ['name' => 'Izul Iskandar Riring', 'username' => '00972F78044'],
    ['name' => 'M. Aditya Patty', 'username' => '00914F16253'],
    ['name' => 'Muklas Patty', 'username' => '00984F09478'],
    ['name' => 'Putri Malika Tuny', 'username' => '00922F88401'],
    ['name' => 'Rainilla Soloto', 'username' => '00994F75167'],
    ['name' => 'Rezky Farhan Kilrey', 'username' => '00946F30285'],
    ['name' => 'Wa Kirana', 'username' => '00840F90663'],
    ['name' => 'Abdul Hair Tawainella', 'username' => '00847F24775'],
    ['name' => 'Andika Basir', 'username' => '00839F37756'],
    ['name' => 'Anjani Wakano', 'username' => '00890F02971'],
    ['name' => 'Dika Patty Lunasiwa', 'username' => '00868F07793'],
    ['name' => 'Fatiah Mussa', 'username' => '00933F15954'],
    ['name' => 'Gita Putri Umasugi', 'username' => '00898F36507'],
    ['name' => 'Hadija Pattimura', 'username' => '00829F95093'],
    ['name' => 'Indi Mussa', 'username' => '00843F62861'],
    ['name' => 'Maul Hayati Kohonussa', 'username' => '00917F07862'],
    ['name' => 'Musfira Zahra Patty', 'username' => '00939F93276'],
    ['name' => 'Rahel Sabila Wemay', 'username' => '00941F33270'],
    ['name' => 'Tahir Wakano', 'username' => '00859F12532'],
    ['name' => 'Teddy Wahyudi Rumbow', 'username' => '30937F99481'],
];

foreach ($muridXIF3 as $murid) {
    User::updateOrCreate(
        ['username' => $murid['username']],
        [
            'name' => $murid['name'],
            'kelas' => 'XI Fase F3',
            'password' => Hash::make($murid['username']),
            'role' => 'murid',
        ]
    );
}

$muridXIIF1 = [
    ['name' => 'Abdul Hamit Mussa', 'username' => '00890G73822'],
    ['name' => 'Ahmad Ramdani Patty', 'username' => '00777G35668'],
    ['name' => 'Aprianti Ukratallo', 'username' => '00891G66709'],
    ['name' => 'Asmaul Husna Patty', 'username' => '00746G40226'],
    ['name' => 'Dastur Patty', 'username' => '00838G20426'],
    ['name' => 'Fadila Bukhari Riring', 'username' => '00711G62798'],
    ['name' => 'Filda Yulia Parihua', 'username' => '00826G74310'],
    ['name' => 'Firza Mustaria Pattimura', 'username' => '00814G15541'],
    ['name' => 'Habil Fahri Keluarani', 'username' => '00835G55344'],
    ['name' => 'Harun Dani Mussa', 'username' => '00877G61031'],
    ['name' => 'Heder Ali Mizwar Patty', 'username' => '00855G60868'],
    ['name' => 'Indahnu Umasugi', 'username' => '00760G73757'],
    ['name' => 'Khairunnisa Mahubessy', 'username' => '00853G55873'],
    ['name' => 'Moh. Zian Tuny', 'username' => '00812G32938'],
    ['name' => 'Mutia Elfani Pattimura', 'username' => '00863G14249'],
    ['name' => 'Nadipa Sakila Pattimura', 'username' => '00863G90683'],
    ['name' => 'Ratna Patty', 'username' => '00633G00560'],
    ['name' => 'Salwa Mussa', 'username' => '00869G28862'],
    ['name' => 'Sauda Pellu', 'username' => '00746G25819'],
    ['name' => 'Siti Nurjannah Patty', 'username' => '00729G26308'],
    ['name' => 'Taufik Ghafanzar Patty', 'username' => '00775G66473'],
    ['name' => 'Taufik Nizar Pattimura', 'username' => '00674G20940'],
    ['name' => 'Umar Dani Pattimura', 'username' => '00791G46257'],
    ['name' => 'Wa Melani', 'username' => '00845G40338'],
    ['name' => 'Wahyu Afrizal', 'username' => '00885G92693'],
    ['name' => 'Darwangi Fitambara', 'username' => '00852G73760'],
];

foreach ($muridXIIF1 as $murid) {
    User::updateOrCreate(
        ['username' => $murid['username']],
        [
            'name' => $murid['name'],
            'kelas' => 'XII Fase F1',
            'password' => Hash::make($murid['username']),
            'role' => 'murid',
        ]
    );
}

$muridXIIF2 = [
    ['name' => 'Ahmad Muzakir Patty', 'username' => '00823G72656'],
    ['name' => 'Akbar Fitriandanu Wenno', 'username' => '00712G49931'],
    ['name' => 'Fadilah Anisa Patty', 'username' => '00879G06011'],
    ['name' => 'Fairus Maulida Patty', 'username' => '00814G41257'],
    ['name' => 'Farid Jihadi Hamit Riring', 'username' => '00887G99499'],
    ['name' => 'Fatur Rahman Ukratallo', 'username' => '00733G84170'],
    ['name' => 'Gamama Wakano', 'username' => '00780G38371'],
    ['name' => 'Gunawan Wakano', 'username' => '00873G36091'],
    ['name' => 'Hairil Anwar Patty', 'username' => '59253G830'],
    ['name' => 'Irgy Fahreza Kulkussa', 'username' => '00752G81180'],
    ['name' => 'Laila Airin Afrianti Sosal', 'username' => '00843G11243'],
    ['name' => 'M. Ibdnu Rhafy Tella', 'username' => '00867G93059'],
    ['name' => 'Muslim Pattimura', 'username' => '00813G71069'],
    ['name' => 'Nurlaili Suneth', 'username' => '00852G69273'],
    ['name' => 'Nur Asmi Pattimura', 'username' => '00813G7106'],
    ['name' => 'Rahma Sarifa Patty', 'username' => '00728G64267'],
    ['name' => 'Ramadhan Kulkussa', 'username' => '00832G25045'],
    ['name' => 'Rizki Saputra Taribuka', 'username' => '00852G72622'],
    ['name' => 'Rosmina Pattimura', 'username' => '00733G26924'],
    ['name' => 'Selma Karami Patty', 'username' => '00873G67382'],
    ['name' => 'Shofiatul Milla Wakano', 'username' => '30866G54942'],
    ['name' => 'Siti Syahrani Patty', 'username' => '00720G59890'],
    ['name' => 'Ulil Zubran Patty', 'username' => '00861G00684'],
    ['name' => 'Usma Ul Hasna Patty', 'username' => '00739G26994'],
    ['name' => 'Fifin Astiani Tupamahu', 'username' => '00835G15677'],
    ['name' => 'Yasser Abutalib Pattimura', 'username' => '00757G66510'],
    ['name' => 'Refa', 'username' => '00622G55616'],
];

foreach ($muridXIIF2 as $murid) {
    User::updateOrCreate(
        ['username' => $murid['username']],
        [
            'name' => $murid['name'],
            'kelas' => 'XII Fase F2',
            'password' => Hash::make($murid['username']),
            'role' => 'murid',
        ]
    );
}

$muridXIIF3 = [
    ['name' => 'Aldi Guteres Ukratallo', 'username' => '30858G05325'],
    ['name' => 'Anaweya Patty', 'username' => '00873G58669'],
    ['name' => 'Andi Sukma Sarti', 'username' => '00847G27036'],
    ['name' => 'Andini Tella Massa', 'username' => '00740G12124'],
    ['name' => 'Anisa Kirana Mussa', 'username' => '00733G85564'],
    ['name' => 'Asril Kaimudin', 'username' => '00877G97150'],
    ['name' => 'Awaludin Patty', 'username' => '00759G02257'],
    ['name' => 'Elyanti Nur Ukratallo', 'username' => '00753G94185'],
    ['name' => 'Farhan Ode', 'username' => '00726G65983'],
    ['name' => 'Maya Wakano', 'username' => '00811G04610'],
    ['name' => 'Nening Kaimudin', 'username' => '00848G37873'],
    ['name' => 'Nur Hamida Riring', 'username' => '00563G45331'],
    ['name' => 'Nur Handayani Sahil', 'username' => '00832G73039'],
    ['name' => 'Nurma Rumbaru', 'username' => '30774G92130'],
    ['name' => 'Muhammad Mizwar', 'username' => '00866G48760'],
    ['name' => 'M. Raad Patty', 'username' => '00721G53779'],
    ['name' => 'Pajriah Mahu', 'username' => '00732G77610'],
    ['name' => 'Rafli Pattiekon', 'username' => '00834G47044'],
    ['name' => 'Rahmat Pattimura', 'username' => '00835G39749'],
    ['name' => 'Airin Aulia Samallo', 'username' => '00892G15600'],
    ['name' => 'Rifaldi Patty', 'username' => '00710G59837'],
    ['name' => 'Sahrul Patty', 'username' => '00671G04669'],
    ['name' => 'Salim Patty', 'username' => '00818G89593'],
    ['name' => 'Suryadi Senja Patty', 'username' => '00835G76277'],
    ['name' => 'Syeruni Patty', 'username' => '00724G65380'],
    ['name' => 'Wa Mifza Fhadilah', 'username' => '00838G67787'],
    ['name' => 'Zibriel Azhar Patty', 'username' => '00820G81972'],
];

foreach ($muridXIIF3 as $murid) {
    User::updateOrCreate(
        ['username' => $murid['username']],
        [
            'name' => $murid['name'],
            'kelas' => 'XII Fase F3',
            'password' => Hash::make($murid['username']),
            'role' => 'murid',
        ]
    );
}


        // Petugas Perpustakaan
        User::updateOrCreate(
            ['username' => 'perpustakaan321'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => Hash::make('perpustakaan321'),
                'role' => 'perpustakaan',
            ]
        );

        // Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
    }
}
