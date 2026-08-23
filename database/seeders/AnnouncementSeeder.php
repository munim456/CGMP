<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        Announcement::query()->firstOrCreate(
            ['message' => 'If you have respiratory symptoms such as cough, cold or flu, please wear a face mask while in the practice. Masks are available at reception.'],
            [
                'title' => 'Health notice',
                'type' => 'info',
                'is_active' => true,
            ]
        );

        Announcement::query()->firstOrCreate(
            ['message' => 'Annual flu vaccination is recommended for everyone aged six months and over, ideally in April or May before the winter season begins. The vaccine offers the best protection in the first three to four months. Chat to your GP about protecting you and your family.'],
            [
                'title' => 'Protect against influenza — vaccines now available',
                'type' => 'info',
                'image' => 'media/placeholders/notice-flu.jpg',
                'button_text' => 'Call to book',
                'button_url' => '/contact',
                'is_active' => true,
            ]
        );

        Announcement::query()->firstOrCreate(
            ['message' => "New patients are always welcome at the practice. Registering with a regular GP means your care is coordinated in one place, with access to longer consultations, chronic disease support and reminders when check-ups are due.\nAsk reception about registering next time you visit."],
            [
                'title' => 'New patients welcome',
                'type' => 'info',
                'image' => 'media/placeholders/notice-mymedicare.jpg',
                'button_text' => 'Find out more',
                'button_url' => '/about',
                'is_active' => true,
            ]
        );
    }
}
