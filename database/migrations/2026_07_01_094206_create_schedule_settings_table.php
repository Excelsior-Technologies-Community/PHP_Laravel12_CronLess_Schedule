<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schedule_settings', function (Blueprint $table) {
            $table->id();
            $table->string('command_signature')->unique();
            $table->string('interval_type')->default('everyMinute');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_settings');
    }
};