<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table): void {
            $table->id();
            $table->uuid()->default(DB::raw('UUID()'));
            $table->string('legacy_id')->nullable();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->boolean('is_highlighted')->default(false);
            $table->timestamps();
            $table->index('slug', 'slug_index');
            $table->index('legacy_id', 'legacy_id_index');
        });
    }

    public function down(): void
    {
        Schema::drop('tags');
    }
};
