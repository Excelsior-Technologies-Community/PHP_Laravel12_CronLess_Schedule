<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('task_execution_logs', function (Blueprint $table) {
            $table->id();
            $table->string('command_signature');
            $table->string('status');
            $table->float('memory_used');
            $table->text('output')->nullable();
            $table->timestamp('executed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_execution_logs');
    }
};