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
        Schema::table('stats', function (Blueprint $table): void {
            $table->string('url', 500)->nullable()->after('sub');
            $table->boolean('open_in_new_tab')->default(false)->after('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stats', function (Blueprint $table): void {
            $table->dropColumn(['url', 'open_in_new_tab']);
        });
    }
};
