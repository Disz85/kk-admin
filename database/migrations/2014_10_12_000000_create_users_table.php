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
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->uuid()->default(DB::raw('UUID()'));
            $table->string('legacy_id')->nullable();
            $table->string('legacy_nickname')->nullable()->index();
            $table->string('legacy_username')->nullable();
            $table->string('sso_id')->nullable()->unique();
            $table->string('title', 10)->nullable();
            $table->string('lastname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('username')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('image_id')->nullable()->constrained('media')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('birth_year')->nullable();
            $table->string('skin_type')->nullable();
            $table->string('skin_concern')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
