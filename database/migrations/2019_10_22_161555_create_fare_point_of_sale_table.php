<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateFarePointOfSaleTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('FarePointOfSale', function (Blueprint $table) {
            $this->createModelColumns($table, false);
            $this->createBelongsToColumn($table, 'Fare');
            $this->createBelongsToColumn($table, 'PointOfSale');

            $table->unsignedInteger('maximumTicketsToSell')->nullable();

            $table->unique(['fareId', 'pointOfSaleId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('FarePointOfSale');
    }
}
