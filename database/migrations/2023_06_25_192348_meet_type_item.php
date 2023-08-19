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
        Schema::create('meet_type_items', function (Blueprint $table) {
            $table->foreignId('meet_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('meet_item_id')->constrained();
            $table->boolean('is_obligatory');
            $table->boolean('is_repeatable');
            $table->unique(['meet_type_id', 'meet_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('meet_type_items');
    }
};
