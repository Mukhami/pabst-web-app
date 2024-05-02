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
        Schema::create('matter_sub_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matter_type_id')->nullable()
                ->references('id')->on('matter_types')
                ->onUpdate('no action')->onDelete('restrict');
            $table->string('key', 50)->unique()->index();
            $table->string('name', 50)->index();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matter_sub_types');
    }
};
