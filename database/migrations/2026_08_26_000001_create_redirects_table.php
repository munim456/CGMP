<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            // Legacy path as visitors typed it, e.g. "contact-us.html" (stored lowercase, no leading slash ambiguity).
            $table->string('source')->unique();
            // New path ("/services") or absolute URL ("https://healthengine.com.au/...").
            $table->string('destination');
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
    }
};
