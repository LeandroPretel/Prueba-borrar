<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateSessionSeatTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('SessionSeat', function (Blueprint $table) {
            $this->createModelColumns($table);
            $this->createBelongsToColumn($table, 'Session');
            $this->createBelongsToColumn($table, 'SessionSector');
            $this->createBelongsToColumn($table, 'Fare', true);

            $table->unsignedSmallInteger('row')->nullable();
            $table->unsignedSmallInteger('column')->nullable();
            $table->string('rowName', 60)->nullable();
            $table->string('number', 30)->nullable();
            $table->boolean('isForDisabled')->default(false);
            $table->boolean('reducedVisibility')->default(false);
            $table->enum('status', ['enabled', 'deleted', 'locked', 'reserved', 'sold', 'hard-ticket', 'invitation'])->default('enabled');
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
        Schema::dropIfExists('SessionSeat');
    }
}
