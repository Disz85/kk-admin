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
        Schema::table('product_change_requests', function (Blueprint $table): void {
            $table->foreignId('user_id')->nullable()->after('data')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('product_change_requests', function (Blueprint $table): void {
            $table->dropForeign('product_change_requests_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }
};
