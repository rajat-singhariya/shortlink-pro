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
        Schema::create('clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_id')->constrained()->cascadeOnDelete();
            $table->string('ip_hash', 64)->nullable();
            $table->string('country', 2)->nullable();
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->text('referrer')->nullable();
            $table->timestamp('clicked_at')->useCurrent();
            $table->index(['link_id', 'clicked_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clicks');
    }
};
