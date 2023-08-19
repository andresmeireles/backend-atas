<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const CALLS = [
        'call',
        'call_release',
        'presiding',
        'driving',
        'recognition',
    ];

    private const HYMNS = [
        'first_hym',
        'intermediary_hym',
        'sacramental_hym',
        'ending_hym',
    ];

    private const SIMPLE_TEXTS = [
        'confirmation',
        'first_pray',
        'first_speaker',
        'second_speaker',
        'third_speaker',
        'ending_pray',
        'testimony',
        'manual_message',
        'message',
        'presenting_child',
        'regent',
        'announcement',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assignment_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('minute_id')->constrained()->cascadeOnDelete();
            $table->enum('label', array_map(fn (string $l) => $l, self::CALLS));
            $table->string('name');
            $table->string('call');
        });
        Schema::create('assignment_hymns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('minute_id')->constrained()->cascadeOnDelete();
            $table->enum('label', array_map(fn (string $l) => $l, self::HYMNS));
            $table->string('name');
            $table->integer('number');
        });
        Schema::create('assignment_simple_texts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('minute_id')->constrained()->cascadeOnDelete();
            $table->enum('label', array_map(fn (string $l) => $l, self::SIMPLE_TEXTS));
            $table->text('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_calls');
        Schema::dropIfExists('assignment_hymns');
        Schema::dropIfExists('assignment_simple_texts');
    }
};
