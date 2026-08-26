<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()->where('role', 'admin')->first();

        $categories = collect([
            ['name' => 'Clinic Updates'],
            ['name' => 'Health Advice'],
        ])->mapWithKeys(function ($c) {
            $category = Category::firstOrCreate(['slug' => \Illuminate\Support\Str::slug($c['name'])], ['name' => $c['name']]);

            return [$c['name'] => $category->id];
        });

        $posts = [
            [
                'title' => 'Respiratory Symptoms? Please Wear a Mask When Visiting',
                'slug' => 'respiratory-symptoms-mask-notice',
                'category' => 'Clinic Updates',
                'excerpt' => 'To keep vulnerable patients safe, we kindly ask anyone with cough, cold or flu symptoms to wear a face mask while in the practice.',
                'body' => '<p>To protect our most vulnerable patients, including elderly community members, babies and people with weakened immune systems, we ask that any patient with <strong>cough, sore throat, runny nose or fever</strong> please wear a face mask while inside the practice.</p><p>Masks are available free of charge at reception. If you have respiratory symptoms, let our reception team know when you arrive so we can arrange for you to wait in a separate area where possible.</p><p>Thank you for helping us keep everyone in our community safe.</p>',
                'meta_description' => 'A kind request: if you have respiratory symptoms such as cough or cold, please wear a mask when visiting Cringila General Medical Practice.',
            ],
            [
                'title' => 'Diabetes Awareness: Know Your Risk',
                'slug' => 'diabetes-awareness-know-your-risk',
                'category' => 'Health Advice',
                'excerpt' => 'Around 1.3 million Australians live with diabetes and many more don’t know they’re at risk. Here’s what to watch for and when to get checked.',
                'body' => '<h2>Why it matters</h2><p>Diabetes is one of the fastest-growing chronic conditions in Australia. Early detection makes an enormous difference: the sooner type 2 diabetes or pre-diabetes is found, the more you can do to prevent complications.</p><h2>Who should be checked?</h2><ul><li>Adults aged 40 and over</li><li>People with a family history of diabetes</li><li>Those who are overweight or carry weight around the waist</li><li>People with high blood pressure or heart disease</li><li>Women who had gestational diabetes</li></ul><h2>Warning signs</h2><p>Increased thirst, frequent urination, unexplained tiredness, blurred vision or slow-healing wounds all deserve a check-up.</p><h2>We can help</h2><p>Our GPs provide diabetes screening, GP Management Plans and referrals to diabetes educators and dietitians. Book a check-up today: same-day appointments are often available.</p>',
                'meta_description' => 'Know your diabetes risk. Learn warning signs and screening advice from the GPs at Cringila General Medical Practice.',
            ],
            [
                'title' => 'Same-Day Appointments & Walk-Ins: How Our Clinic Works',
                'slug' => 'same-day-appointments-and-walk-ins',
                'category' => 'Clinic Updates',
                'excerpt' => 'Open five days a week with same-day appointments and walk-ins welcome: here’s how to be seen quickly at CGMP.',
                'body' => '<p>We know health concerns don’t always fit neatly into a schedule. That’s why Cringila General Medical Practice keeps <strong>same-day appointments</strong> available every day we’re open, and <strong>walk-ins are always welcome</strong>.</p><h2>The fastest ways to be seen</h2><ol><li><strong>Book online</strong> through HealthEngine, choose a time that suits you.</li><li><strong>Call reception</strong> and ask for the next available appointment.</li><li><strong>Walk in</strong>, patients with bookings are seen first, but we’ll always fit urgent concerns in.</li></ol><p>If your matter is urgent, tell reception on arrival so we can prioritise appropriately.</p>',
                'meta_description' => 'How to be seen quickly at Cringila General Medical Practice: same-day appointments, online booking via HealthEngine and walk-in visits.',
            ],
        ];

        foreach ($posts as $i => $post) {
            Post::query()->firstOrCreate(
                ['slug' => $post['slug']],
                [
                    'author_id' => $author?->id,
                    'category_id' => $categories[$post['category']],
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'status' => 'published',
                    'published_at' => now()->subDays($i * 9),
                    'meta_title' => null,
                    'meta_description' => $post['meta_description'],
                ]
            );
        }
    }
}
