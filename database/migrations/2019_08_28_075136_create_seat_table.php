<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateSeatTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Seat', function (Blueprint $table) {
            $this->createModelColumns($table, false, false);
            $this->createBelongsToColumn($table, 'Sector');

            $table->unsignedSmallInteger('row')->nullable();
            $table->unsignedSmallInteger('column')->nullable();
            $table->string('rowName', 60)->nullable();
            $table->string('number', 30)->nullable();
            $table->boolean('isForDisabled')->default(false);
            $table->boolean('reducedVisibility')->default(false);
            $table->enum('status', ['enabled', 'disabled', 'deleted', 'locked', 'reserved'])->default('enabled');
            $table->text('lockReason')->nullable();
            $table->text('observations')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('Seat');
    }
}
