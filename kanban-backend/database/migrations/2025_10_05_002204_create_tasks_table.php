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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // 
            $table->string('taskName', 255)->nullable(false); 
            $table->text('description')->nullable(false); 
            $table->dateTime('startDate')->nullable(false); 
            $table->dateTime('endDate')->nullable(false); 
            $table->string('allocator', 50)->nullable(false); 
            $table->string('employee', 75)->nullable(false); 
            $table->string('priority', 25)->nullable(false);
            $table->integer('progress')->nullable(false);
            // Definición de la llave foránea
            $table->foreignId('card_id')->constrained('cards')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
