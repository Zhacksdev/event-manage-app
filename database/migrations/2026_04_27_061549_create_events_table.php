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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['seminar', 'lomba', 'workshop']);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('organizer_user_id');
            $table->integer('quota');
            $table->integer('registered_count')->default(0);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location');
            $table->enum('status', ['open', 'closed', 'cancelled'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
