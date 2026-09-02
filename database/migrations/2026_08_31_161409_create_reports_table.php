<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->String('reporter_key')->nullable()->index();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            
            $table->string('title');
            $table->text('description');
            $table->string('image');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('location');
            
            // Kolom Hasil AI & Metrik Laporan
            $table->string('severity')->nullable();
            $table->string('urgency')->nullable();
            $table->decimal('priority_score', 8, 2)->default(0);
            $table->text('potential_risk')->nullable();
            $table->text('ai_masyarakat')->nullable();
            $table->text('ai_adm')->nullable();

            
            // Status Laporan
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('reports');
    }
};