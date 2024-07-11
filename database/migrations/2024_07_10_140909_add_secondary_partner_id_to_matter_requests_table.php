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
            $table->foreignId('secondary_partner_id')
                ->nullable()->after('partner_id')
                ->references('id')->on('users')
                ->onDelete('restrict')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matter_requests', function (Blueprint $table) {
            $table->dropForeign('secondary_partner_id');
            $table->dropColumn('secondary_partner_id');
        });
    }
};
