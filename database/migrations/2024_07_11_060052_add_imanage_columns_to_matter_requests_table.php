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
        Schema::table('matter_requests', function (Blueprint $table) {
            $table->boolean('approved')->default(false)->after('reviewed_date');
            $table->json('matter_create_response')->nullable()->after('related_cases');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matter_requests', function (Blueprint $table) {
            $table->dropColumn('approved');
            $table->dropColumn('matter_create_response');
        });
    }
};
