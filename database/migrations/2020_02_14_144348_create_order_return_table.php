<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateOrderReturnTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('OrderReturn', function (Blueprint $table) {
            $this->createModelColumns($table);
            $this->createBelongsToColumn($table, 'OrderReturnReason');
            $this->createBelongsToColumn($table, 'PointOfSale', true);
            $this->createBelongsToColumn($table, 'Client', true);

            $table->decimal('amount', 8, 2)->default(0);
            $table->enum('mode', ['tpv', 'transfer', 'cash'])->default('cash');
            $table->dateTime('attemptDate')->nullable();
            $table->dateTime('date')->nullable();
            $table->enum('status', ['attempt', 'successful', 'failed'])->default('attempt');
            $table->boolean('returnDistribution')->default(true);
            $table->unsignedInteger('redsysNumber')->nullable();
            $table->string('authorizationCode')->nullable();
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
        Schema::dropIfExists('OrderReturn');
    }
}
