<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('report_upvotes', function (Blueprint $table) {
            $table->id();
            
            // FK mengarah ke reports.id
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            
            $table->string('voter_key'); // Menyimpan IP Address / Session ID warga tanpa login
            $table->timestamps();

            // Mencegah 1 voter_key melakukan vote berkali-kali pada 1 laporan
            $table->unique(['report_id', 'voter_key']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('report_upvotes');
    }
};