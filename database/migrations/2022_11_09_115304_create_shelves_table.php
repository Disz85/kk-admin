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
        Schema::create('shelves', function (Blueprint $table): void {
            $table->id();
            $table->uuid()->default(DB::raw('UUID()'));
            $table->string('title');
            $table->string('slug');
            $table->boolean('is_private')->default(true);
            $table->foreignId('user_id')->nullable()->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();

            $table->unique([ 'title', 'user_id' ]);
            $table->unique([ 'slug', 'user_id' ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('shelves');
    }
};
