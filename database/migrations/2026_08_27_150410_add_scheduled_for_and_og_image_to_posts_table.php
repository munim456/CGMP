<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->timestamp('scheduled_for')->nullable()->after('published_at')->index();
            $table->string('og_image')->nullable()->after('meta_description');
            $table->string('featured_image_thumb')->nullable()->after('featured_image');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['scheduled_for', 'og_image', 'featured_image_thumb']);
        });
    }
};
