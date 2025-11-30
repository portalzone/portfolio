<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_ip_hash'); // Hashed for privacy
            $table->text('user_agent')->nullable();
            $table->string('page_url');
            $table->string('referrer')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->timestamp('visited_at');
            $table->index(['page_url', 'visited_at']);
            $table->index('visited_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};