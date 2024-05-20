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
            $table->foreignId('conflict_user_id')
                ->nullable()->after('docketing_user_id')
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
            $table->dropForeign('conflict_user_id');
            $table->dropColumn('conflict_user_id');
        });
    }
};
