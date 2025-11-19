<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('addon_slug'); // pdf-converter, image-converter, etc.
            $table->string('action'); // convert, upload, download, etc.
            $table->string('tool')->nullable(); // specific tool used (pdf-to-word, etc.)
            $table->json('metadata')->nullable(); // Additional data like file sizes, formats, etc.
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->boolean('success')->default(true);
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'addon_slug']);
            $table->index(['addon_slug', 'action']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usage_logs');
    }
};
