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
        Schema::create('matter_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responsible_attorney_id')->nullable()->references('id')->on('users')->onDelete('restrict')->onUpdate('no action');
            $table->foreignId('matter_type_id')->nullable()->references('id')->on('matter_types')->onDelete('restrict')->onUpdate('no action');
            $table->foreignId('sub_type_id')->nullable()->references('id')->on('matter_sub_types')->onDelete('restrict')->onUpdate('no action');
            $table->foreignId('additional_staff_id')->nullable()->references('id')->on('users')->onDelete('restrict')->onUpdate('no action');
            $table->string('ppg_client_matter_no')->nullable();
            $table->string('ppg_ref')->nullable();
            $table->string('client_ref')->nullable();
            $table->string('client_name')->index()->nullable();
            $table->string('client_main_contact')->nullable();
            $table->string('client_secondary_contacts')->nullable();
            $table->string('title_of_invention')->index()->nullable();
             $table->date('bar_date')->nullable();
            $table->date('goal_date')->nullable();
            $table->date('conversion_date')->nullable();
            $table->text('inventors')->nullable();
            $table->text('licensees')->nullable();
            $table->text('assignees')->nullable();
            $table->text('co_owners')->nullable();
            $table->text('adverse_parties')->nullable();
            $table->string('entity_size')->nullable();
            $table->boolean('renewal_fees_handled_elsewhere')->default(false);
            $table->text('other_related_parties')->nullable();
            $table->text('key_terms_for_conflict_search')->nullable();
            $table->foreignId('conducted_by')->nullable()->references('id')->on('users')->onDelete('restrict')->onUpdate('no action');
            $table->date('conducted_date')->nullable();
            $table->foreignId('reviewed_by')->nullable()->references('id')->on('users')->onDelete('restrict')->onUpdate('no action');
            $table->date('reviewed_date')->nullable();
            $table->text('conflict_search_needed_explanation')->nullable();
            $table->text('related_cases')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matter_requests');
    }
};
