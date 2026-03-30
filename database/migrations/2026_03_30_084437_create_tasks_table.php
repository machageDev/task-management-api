<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the tasks table.
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // integer Primary Key [cite: 15, 16, 17]
            $table->string('title'); // Task title [cite: 18, 19, 20]
            $table->date('due_date'); // Deadline [cite: 21, 22, 23]
            
            // priority: must be low, medium, or high [cite: 24, 25, 26, 42]
            $table->enum('priority', ['low', 'medium', 'high']); 
            
            // status: pending, in_progress, or done [cite: 27, 28, 29, 30]
            $table->enum('status', ['pending', 'in_progress', 'done'])->default('pending'); 
            
            $table->timestamps(); // Laravel default created_at and updated_at [cite: 31, 33, 34, 36]
            
            // Business Rule: title cannot duplicate a task with the same due_date 
            $table->unique(['title', 'due_date']); 
        });
    }

    /**
     * Reverse the migrations (Delete the table).
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};