<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateTicketTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Ticket', function (Blueprint $table) {
            $this->createModelColumns($table);
            $this->createBelongsToColumn($table, 'Session');
            $this->createBelongsToColumn($table, 'Order');
            $this->createBelongsToColumn($table, 'SessionSeat', true);
            $this->createBelongsToColumn($table, 'SessionAreaFare', true);
/*
            $table->uuid('sessionAreaFareId')->nullable();
            $table->foreign('sessionAreaFareId')
                ->references('id')
                ->on('SessionAreaFare')
                ->onDelete('cascade')
                ->onUpdate('cascade');
 */

            $this->createBelongsToColumn($table, 'OrderReturn', true);

            $table->string('locator', 8)->index();
            $table->unsignedBigInteger('number')->nullable();
            $table->decimal('baseAmount', 8, 2)->default(0);
            $table->decimal('baseAmountWithDiscount', 8, 2)->default(0);
            $table->decimal('distributionAmount', 8, 2)->default(0);
            $table->decimal('distributionAmountWithDiscount', 8, 2)->default(0);
            $table->decimal('amount', 8, 2)->default(0);
            $table->decimal('amountWithDiscount', 8, 2)->default(0);

            // Columns to access control
            // $table->enum('status', ['pending', 'used'])->default('pending');
            // $table->dateTime('entryDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('Ticket');
    }
}
