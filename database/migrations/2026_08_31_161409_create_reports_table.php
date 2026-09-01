<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('image_path');
            $table->string('location_name');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Output Analisis Gemini AI
            $table->text('ai_summary')->nullable();
            $table->text('ai_safety_advice')->nullable();
            $table->text('ai_gov_action')->nullable();
            $table->integer('ai_severity_score')->default(1); // Skala 1 - 100
            
            // Prioritas & Metrik Publik
            $table->unsignedInteger('upvote_count')->default(0);
            $table->decimal('priority_score', 8, 2)->default(0);
            $table->enum('status', ['unverified', 'verified', 'in_progress', 'resolved'])->default('unverified');
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('reports');
    }
};