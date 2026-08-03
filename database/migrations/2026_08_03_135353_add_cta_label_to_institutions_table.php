<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Custom label for the main registration CTA button on the public PPDB
     * page of a jenjang (falls back to a mode-aware default when null).
     */
    public function up(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->string('cta_label')->nullable()->after('form_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn('cta_label');
        });
    }
};
