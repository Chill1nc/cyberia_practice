<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('publisher')->nullable();
            $table->integer('pages')->nullable();
            $table->string('size')->nullable();
            $table->string('cover_type')->nullable();
            $table->integer('weight')->nullable();
            $table->string('age_limit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['publisher', 'pages', 'size', 'cover_type', 'weight', 'age_limit']);
        });
    }
};
