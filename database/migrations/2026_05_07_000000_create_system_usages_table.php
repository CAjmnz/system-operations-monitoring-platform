<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('system_usages', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('route');
            $table->string('method');
            $table->string('ip_address');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_usages');
    }
};
