<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateSessionTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Session', function (Blueprint $table) {
            $this->createModelColumns($table);
            $this->createBelongsToColumn($table, 'Show');
            $this->createBelongsToColumn($table, 'Place');
            $this->createBelongsToColumn($table, 'Space');
            $this->createBelongsToColumn($table, 'ShowTemplate');

            $table->boolean('isActive')->default(false);
            $table->enum('status', ['A la venta', 'Agotada', 'Finalizada', 'Cancelada'])->default('A la venta');
            $table->boolean('isHidden')->default(false);
            // Main session date
            $table->dateTime('date');
            // Display as 'Soon/Próximamente'
            $table->boolean('displayAsSoon')->default(false);
            // Advance sale
            $table->boolean('advanceSaleEnabled')->default(true);
            $table->dateTime('advanceSaleStartDate')->nullable();
            $table->dateTime('advanceSaleFinishDate')->nullable();
            // Assisted close
            $table->dateTime('assistedSellEndDate')->nullable();
            // Pick up dates in ticketOffices
            $table->boolean('ticketOfficesEnabled')->default(false);
            $table->dateTime('ticketOfficesStartDate')->nullable();
            $table->dateTime('ticketOfficesEndDate')->nullable();
            // Pick up dates in pointOfSale
            $table->boolean('pickUpInPointsOfSaleEnabled')->default(false);
            $table->dateTime('pickUpInPointsOfSaleStartDate')->nullable();
            $table->dateTime('pickUpInPointsOfSaleEndDate')->nullable();
            // Access control
            $table->dateTime('openingDoorsDate')->nullable();
            $table->dateTime('closingDoorsDate')->nullable();
            // Vat of the tickets, (default the ShowCategory vat)
            $table->decimal('vat', 6, 2)->default(0.00);

            $table->boolean('isLiquidated')->default(false);

            $table->decimal('automaticDistributionPercentage', 8, 2)->nullable();
            $table->decimal('automaticDistributionMinimum', 8, 2)->nullable();
            $table->decimal('automaticDistributionMaximum', 8, 2)->nullable();
            // Commissions
            $table->decimal('pointOfSaleCommissionPercentage', 8, 2)->nullable();
            $table->decimal('pointOfSaleCommissionMinimum', 8, 2)->nullable();
            $table->decimal('pointOfSaleCommissionMaximum', 8, 2)->nullable();

            $table->decimal('printCommissionPercentage', 8, 2)->nullable();
            $table->decimal('printCommissionMinimum', 8, 2)->nullable();
            $table->decimal('printCommissionMaximum', 8, 2)->nullable();

            // External
            $table->boolean('isExternal')->default(false);
            $table->string('externalUrl')->nullable();
            $table->uuid('externalEnterpriseId')->nullable();
            $table->foreign('externalEnterpriseId', 'externalEnterpriseId')
                ->references('id')->on('Enterprise')
                ->onDelete('set null')
                ->onUpdate('set null');
            // Cancelled
            $table->boolean('returnExpensesWhenCancelled')->default(false);
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
        Schema::dropIfExists('Session');
    }
}
