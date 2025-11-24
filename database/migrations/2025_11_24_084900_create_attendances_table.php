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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
        $table->date('date'); // full date e.g. 2025-11-01
        $table->time('time_in')->nullable(); // jam hadir
            $table->enum('status', ['hadir','sakit','izin','dd','dl'])->default('hadir');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['employee_id','date']);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
