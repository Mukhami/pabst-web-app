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
            $table->enum('status', [
                \App\Models\MatterRequest::STATUS_TYPE_DRAFT,
                \App\Models\MatterRequest::STATUS_TYPE_SUBMITTED
            ])
                ->default(\App\Models\MatterRequest::STATUS_TYPE_SUBMITTED)
                ->after('matter_create_response');

            $table->boolean('approval_flow_started')->default(true)->after('matter_create_response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matter_requests', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('approval_flow_started');
        });
    }
};
