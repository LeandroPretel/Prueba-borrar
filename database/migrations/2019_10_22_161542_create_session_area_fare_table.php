<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateSessionAreaFareTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('SessionAreaFare', function (Blueprint $table) {
            $this->createModelColumns($table, false);
            $this->createBelongsToColumn($table, 'SessionArea');
            $this->createBelongsToColumn($table, 'Fare');

            $table->boolean('isActive')->default(true);

            $table->decimal('earlyPrice', 6, 2)->nullable();
            $table->decimal('earlyDistributionPrice', 6, 2)->nullable();
            $table->decimal('earlyTotalPrice', 6, 2)->nullable();

            $table->decimal('ticketOfficePrice', 6, 2)->nullable();
            $table->decimal('ticketOfficeDistributionPrice', 6, 2)->nullable();
            $table->decimal('ticketOfficeTotalPrice', 6, 2)->nullable();

            $table->unique(['sessionAreaId', 'fareId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('SessionAreaFare');
    }
}
