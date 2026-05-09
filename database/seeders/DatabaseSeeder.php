<?php

namespace Database\Seeders;

use App\Models\Assistant;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin Lab',
            'email' => 'pelitabangsalab@gmail.com',
            'password' => Hash::make('lablablab++123'),
        ]);

        // Create courses
        $dataMining = Course::create([
            'name' => 'Data Mining',
            'code' => 'DM',
            'color' => '#6366F1',
            'semester' => 'Genap 2024/2025',
        ]);

        $basisData = Course::create([
            'name' => 'Basis Data',
            'code' => 'BD',
            'color' => '#EC4899',
            'semester' => 'Genap 2024/2025',
        ]);

        // Create assistants
        $fajar = Assistant::create(['name' => 'Fajar Agung', 'nim' => '20.01.001']);
        $romdhon = Assistant::create(['name' => 'Romdhon', 'nim' => '20.01.002']);
        $arya = Assistant::create(['name' => 'Arya Wiratama', 'nim' => '21.01.003']);
        $dinda = Assistant::create(['name' => 'Dinda Safitri', 'nim' => '21.01.004']);
        $rizky = Assistant::create(['name' => 'Rizky Pratama', 'nim' => '21.01.005']);
        $nadia = Assistant::create(['name' => 'Nadia Putri', 'nim' => '22.01.006']);

        // ========== DATA MINING SCHEDULES ==========
        $dm1 = Schedule::create([
            'course_id' => $dataMining->id,
            'angkatan' => '2023',
            'program' => 'Reguler',
            'kelas' => 'TI.23.A.1',
            'hari' => 'Senin',
            'waktu_mulai' => '08.00',
            'waktu_selesai' => '10.00',
            'dosen' => 'Suherman, S.Kom., M.Kom.',
            'ruangan' => 'Lab 1',
        ]);
        $dm1->assistants()->attach([$fajar->id, $romdhon->id]);

        $dm2 = Schedule::create([
            'course_id' => $dataMining->id,
            'angkatan' => '2023',
            'program' => 'Reguler',
            'kelas' => 'TI.23.A.2',
            'hari' => 'Senin',
            'waktu_mulai' => '10.30',
            'waktu_selesai' => '12.30',
            'dosen' => 'Suherman, S.Kom., M.Kom.',
            'ruangan' => 'Lab 1',
        ]);
        $dm2->assistants()->attach([$arya->id, $dinda->id]);

        $dm3 = Schedule::create([
            'course_id' => $dataMining->id,
            'angkatan' => '2023',
            'program' => 'Reguler',
            'kelas' => 'TI.23.B.1',
            'hari' => 'Selasa',
            'waktu_mulai' => '08.00',
            'waktu_selesai' => '10.00',
            'dosen' => 'Dr. Andi Wijaya, M.T.',
            'ruangan' => 'Lab 2',
        ]);
        $dm3->assistants()->attach([$rizky->id, $nadia->id]);

        $dm4 = Schedule::create([
            'course_id' => $dataMining->id,
            'angkatan' => '2023',
            'program' => 'Ekstensi',
            'kelas' => 'TI.23.E.1',
            'hari' => 'Rabu',
            'waktu_mulai' => '13.00',
            'waktu_selesai' => '15.00',
            'dosen' => 'Suherman, S.Kom., M.Kom.',
            'ruangan' => 'Lab 1',
        ]);
        $dm4->assistants()->attach([$fajar->id, $arya->id]);

        $dm5 = Schedule::create([
            'course_id' => $dataMining->id,
            'angkatan' => '2023',
            'program' => 'Reguler',
            'kelas' => 'TI.23.A.3',
            'hari' => 'Kamis',
            'waktu_mulai' => '08.00',
            'waktu_selesai' => '10.00',
            'dosen' => 'Dr. Andi Wijaya, M.T.',
            'ruangan' => 'Lab 3',
        ]);
        $dm5->assistants()->attach([$dinda->id, $romdhon->id]);

        // ========== BASIS DATA SCHEDULES ==========
        $bd1 = Schedule::create([
            'course_id' => $basisData->id,
            'angkatan' => '2024',
            'program' => 'Reguler',
            'kelas' => 'TI.24.A.1',
            'hari' => 'Selasa',
            'waktu_mulai' => '10.30',
            'waktu_selesai' => '12.30',
            'dosen' => 'Ir. Budi Santoso, M.Kom.',
            'ruangan' => 'Lab 2',
        ]);
        $bd1->assistants()->attach([$fajar->id, $nadia->id]);

        $bd2 = Schedule::create([
            'course_id' => $basisData->id,
            'angkatan' => '2024',
            'program' => 'Reguler',
            'kelas' => 'TI.24.A.2',
            'hari' => 'Rabu',
            'waktu_mulai' => '08.00',
            'waktu_selesai' => '10.00',
            'dosen' => 'Ir. Budi Santoso, M.Kom.',
            'ruangan' => 'Lab 1',
        ]);
        $bd2->assistants()->attach([$romdhon->id, $rizky->id]);

        $bd3 = Schedule::create([
            'course_id' => $basisData->id,
            'angkatan' => '2024',
            'program' => 'Reguler',
            'kelas' => 'TI.24.B.1',
            'hari' => 'Kamis',
            'waktu_mulai' => '10.30',
            'waktu_selesai' => '12.30',
            'dosen' => 'Dra. Siti Rahayu, M.Cs.',
            'ruangan' => 'Lab 3',
        ]);
        $bd3->assistants()->attach([$arya->id, $dinda->id]);

        $bd4 = Schedule::create([
            'course_id' => $basisData->id,
            'angkatan' => '2024',
            'program' => 'Ekstensi',
            'kelas' => 'TI.24.E.1',
            'hari' => 'Jumat',
            'waktu_mulai' => '13.00',
            'waktu_selesai' => '15.00',
            'dosen' => 'Ir. Budi Santoso, M.Kom.',
            'ruangan' => 'Lab 2',
        ]);
        $bd4->assistants()->attach([$fajar->id, $rizky->id]);

        $bd5 = Schedule::create([
            'course_id' => $basisData->id,
            'angkatan' => '2024',
            'program' => 'Reguler',
            'kelas' => 'TI.24.A.3',
            'hari' => 'Jumat',
            'waktu_mulai' => '08.00',
            'waktu_selesai' => '10.00',
            'dosen' => 'Dra. Siti Rahayu, M.Cs.',
            'ruangan' => 'Lab 1',
        ]);
        $bd5->assistants()->attach([$nadia->id, $romdhon->id]);
    }
}
