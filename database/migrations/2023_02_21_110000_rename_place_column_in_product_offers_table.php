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
        Schema::table('product_offers', function (Blueprint $table): void {
            $table->renameColumn('place', 'where_to_find');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('product_offers', function (Blueprint $table): void {
            $table->renameColumn('where_to_find', 'place');
        });
    }
};
