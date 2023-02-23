<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table): void {
            $table->id();
            $table->uuid()->default(DB::raw('UUID()'));
            $table->unsignedBigInteger('legacy_id')->nullable();
            $table->string('legacy_url', 2000)->nullable();
            $table->text('path');
            $table->string('type', 100);
            $table->string('title')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('x')->nullable();
            $table->integer('y')->nullable();
            $table->timestamps();
            $table->index('path');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
