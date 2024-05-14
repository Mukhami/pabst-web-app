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
            $table->foreignId('docketing_user_id')
                ->nullable()->after('responsible_attorney_id')
                ->references('id')->on('users')
                ->onDelete('restrict')->onUpdate('no action');

            $table->foreignId('partner_id')
                ->nullable()->after('responsible_attorney_id')
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
            $table->dropForeign('docketing_user_id');
            $table->dropForeign('partner_id');

            $table->dropColumn('docketing_user_id');
            $table->dropColumn('partner_id');
        });
    }
};
