<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::create([
            'title' => 'Seminar AI',
            'type' => 'seminar',
            'description' => 'Belajar Artificial Intelligence',
            'organizer_user_id' => 1,
            'quota' => 100,
            'start_date' => '2026-05-01 08:00:00',
            'end_date' => '2026-05-01 12:00:00',
            'location' => 'Aula Kampus',
            'status' => 'open'
        ]);

        Event::create([
            'title' => 'Lomba Coding',
            'type' => 'lomba',
            'description' => 'Kompetisi coding mahasiswa',
            'organizer_user_id' => 2,
            'quota' => 50,
            'start_date' => '2026-06-10 09:00:00',
            'end_date' => '2026-06-10 15:00:00',
            'location' => 'Lab Komputer',
            'status' => 'open'
        ]);

        Event::create([
            'title' => 'Workshop UI UX',
            'type' => 'workshop',
            'description' => 'Belajar desain aplikasi modern',
            'organizer_user_id' => 1,
            'quota' => 80,
            'start_date' => '2026-05-15 10:00:00',
            'end_date' => '2026-05-15 14:00:00',
            'location' => 'Ruang Multimedia',
            'status' => 'open'
        ]);

        Event::create([
            'title' => 'Seminar Cyber Security',
            'type' => 'seminar',
            'description' => 'Keamanan data dan jaringan',
            'organizer_user_id' => 3,
            'quota' => 120,
            'start_date' => '2026-07-01 08:00:00',
            'end_date' => '2026-07-01 11:30:00',
            'location' => 'Gedung A',
            'status' => 'open'
        ]);

        Event::create([
            'title' => 'Lomba Business Plan',
            'type' => 'lomba',
            'description' => 'Kompetisi ide bisnis kreatif',
            'organizer_user_id' => 4,
            'quota' => 40,
            'start_date' => '2026-07-12 09:00:00',
            'end_date' => '2026-07-12 16:00:00',
            'location' => 'Hall Kampus',
            'status' => 'open'
        ]);

        Event::create([
            'title' => 'Workshop Public Speaking',
            'type' => 'workshop',
            'description' => 'Melatih kemampuan berbicara',
            'organizer_user_id' => 2,
            'quota' => 60,
            'start_date' => '2026-06-18 13:00:00',
            'end_date' => '2026-06-18 16:00:00',
            'location' => 'Ruang Seminar',
            'status' => 'open'
        ]);

        Event::create([
            'title' => 'Seminar Startup Digital',
            'type' => 'seminar',
            'description' => 'Membangun bisnis digital',
            'organizer_user_id' => 5,
            'quota' => 150,
            'start_date' => '2026-08-03 09:00:00',
            'end_date' => '2026-08-03 12:00:00',
            'location' => 'Auditorium',
            'status' => 'open'
        ]);

        Event::create([
            'title' => 'Lomba Mobile App',
            'type' => 'lomba',
            'description' => 'Kompetisi aplikasi mobile',
            'organizer_user_id' => 1,
            'quota' => 30,
            'start_date' => '2026-09-10 08:00:00',
            'end_date' => '2026-09-10 17:00:00',
            'location' => 'Lab IT',
            'status' => 'open'
        ]);

        Event::create([
            'title' => 'Workshop Video Editing',
            'type' => 'workshop',
            'description' => 'Belajar editing video profesional',
            'organizer_user_id' => 3,
            'quota' => 45,
            'start_date' => '2026-08-20 10:00:00',
            'end_date' => '2026-08-20 14:00:00',
            'location' => 'Studio Multimedia',
            'status' => 'open'
        ]);

        Event::create([
            'title' => 'Seminar Leadership',
            'type' => 'seminar',
            'description' => 'Membangun jiwa kepemimpinan',
            'organizer_user_id' => 2,
            'quota' => 90,
            'start_date' => '2026-10-05 09:00:00',
            'end_date' => '2026-10-05 12:00:00',
            'location' => 'Gedung Serbaguna',
            'status' => 'open'
        ]);
    }
}