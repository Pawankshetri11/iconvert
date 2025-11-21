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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Unique identifier for the content
            $table->string('group')->default('general'); // Group for organizing content (header, footer, landing, etc.)
            $table->string('type')->default('text'); // text, html, json
            $table->text('value'); // The actual content
            $table->string('label')->nullable(); // Human readable label
            $table->text('description')->nullable(); // Description of what this content is for
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['group', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
