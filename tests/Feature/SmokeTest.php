<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function seedAll(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_homepage_renders_with_seeded_content(): void
    {
        $this->seedAll();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Cringila General Medical Practice');
        $response->assertSee('Latest from the practice');
        $response->assertSee('New patients welcome');
    }

    public function test_public_pages_render(): void
    {
        $this->seedAll();

        foreach (['about', 'doctors', 'services', 'blog', 'book-appointment', 'contact', 'privacy-policy'] as $path) {
            $this->get('/' . $path)->assertOk();
        }
    }

    public function test_service_page_renders(): void
    {
        $this->seedAll();

        $this->get('/services/mental-health-care')->assertOk();
    }

    public function test_blog_post_renders(): void
    {
        $this->seedAll();

        $this->get('/blog/diabetes-awareness-know-your-risk')->assertOk();
    }

    public function test_unknown_page_returns_custom_404(): void
    {
        $this->seedAll();

        $this->get('/no-such-page')->assertNotFound();
    }

    public function test_guest_hitting_admin_is_redirected_to_login(): void
    {
        $this->get('/admin/posts')->assertRedirect(route('admin.login'));
    }
}
