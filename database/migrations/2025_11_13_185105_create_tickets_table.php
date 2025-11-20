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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kategori_gangguan_id')->nullable()->constrained()->onDelete('set null');                        
            $table->string('kategori_gangguan_nama')->nullable();
            $table->string('kategori_pelanggan_nama')->nullable();
            $table->string('judul');
            $table->text('deskripsi');
            $table->enum('prioritas', ['Rendah', 'Sedang', 'Tinggi'])->nullable();
            $table->float('ml_confidence')->nullable();
            $table->json('ml_features')->nullable();
            $table->timestamp('ml_predicted_at')->nullable();
            $table->enum('status', ['Menunggu', 'Diproses', 'Selesai'])->default('Menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'kategori_gangguan_nama',
                'kategori_pelanggan_nama',
                'ml_features',
                'ml_predicted_at'
            ]);
            $table->renameColumn('ml_confidence', 'confidence');
        });
    }
};
