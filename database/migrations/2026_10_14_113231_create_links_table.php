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
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->text('original_url');
            $table->string('short_code', 32)->unique();
            $table->string('type')->default('other');
            $table->boolean('is_safe')->default(true);
            $table->string('status')->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->string('creator_ip_hash', 64)->nullable();
            $table->string('country', 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
