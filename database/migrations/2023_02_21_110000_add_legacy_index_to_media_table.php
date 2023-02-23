<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table): void {
            $table->index('legacy_id', 'idx_legacy_id');
            $table->index('legacy_url', 'idx_legacy_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table): void {
            $table->dropIndex('idx_legacy_id');
            $table->dropIndex('idx_legacy_url');
        });
    }
};
