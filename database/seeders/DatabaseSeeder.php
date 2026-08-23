<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            SettingSeeder::class,
            SectionSeeder::class,
            PageSeeder::class,
            ServiceSeeder::class,
            DoctorSeeder::class,
            PostSeeder::class,
            AnnouncementSeeder::class,
            TestimonialSeeder::class,
        ]);
    }
}
