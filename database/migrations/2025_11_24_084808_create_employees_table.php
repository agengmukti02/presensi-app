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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 18);
            $table->string('nama');
            $table->string('pangkat');
            $table->string('golongan');
            $table->string('jabatan');
            $table->enum('status_pegawai', ['PNS', 'PPPK']);
            $table->string('kedudukan');
            $table->timestamps();

            $table->foreign('id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
