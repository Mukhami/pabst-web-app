<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\MatterRequestApproval;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('matter_request_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matter_request_id')->references('id')->on('matter_requests')->onDelete('restrict')->onUpdate('no action');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('no action');
            $table->string('approval_type')->nullable();
            $table->enum('status', [
                MatterRequestApproval::STATUS_PENDING,
                MatterRequestApproval::STATUS_APPROVED,
                MatterRequestApproval::STATUS_CHANGES_REQUESTED,
                MatterRequestApproval::STATUS_REJECTED,
                ])->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matter_request_approvals');
    }
};
