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
        Schema::create('picklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('picklist_id')->nullable()
                ->references('id')->on('picklists')
                ->onUpdate('no action')->onDelete('restrict');
            $table->string('label', 50)->index();
            $table->text('description')->nullable();
            $table->string('identifier', 100)->index();
            $table->integer('sequence')->default(0);
            $table->boolean('status')->default(true);
            $table->boolean('is_system')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picklist_items');
    }
};
