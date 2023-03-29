<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ingredients', function (Blueprint $table): void {
            $table->boolean('is_top')->default(false);
            $table->index('is_top', 'idx_is_top');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table): void {
            $table->dropIndex('idx_is_top');
            $table->dropColumn('is_top');
        });
    }
};
