<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateDoorSeatTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('DoorSeat', function (Blueprint $table) {
            $this->createModelColumns($table, false);
            $this->createBelongsToColumn($table, 'Door');
            $this->createBelongsToColumn($table, 'Seat');

            $table->unique(['doorId', 'seatId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('DoorSeat');
    }
}
