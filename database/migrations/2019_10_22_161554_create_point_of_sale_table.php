<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreatePointOfSaleTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('PointOfSale', function (Blueprint $table) {
            $this->createModelColumns($table);
            $table->uuid('countryId')->nullable();
            $table->foreign('countryId', 'countryId')
                ->references('id')->on('Zone')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->uuid('provinceId')->nullable();
            $table->foreign('provinceId', 'provinceId')
                ->references('id')->on('Zone')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->boolean('isWeb')->default(false);
            $table->boolean('isAutomatic')->default(false);
            $table->boolean('isAssisted')->default(false);
            $table->boolean('isByPhone')->default(false);
            $table->string('name');
            $table->string('webName');
            $table->string('ticketName');
            $table->string('slug');
            $table->boolean('isActive')->default(true);
            $table->boolean('isVisible')->default(true);
            $table->boolean('isMaster')->default(false);
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('zipCode')->nullable();
            $table->string('phone')->nullable();
            $table->text('openingHours')->nullable();
            // Payment methods
            $table->boolean('creditCardEnabled')->default(false);
            $table->boolean('cashEnabled')->default(false);
            // Services
            $table->boolean('ticketPickUpEnabled')->default(false);
            $table->boolean('serviceServedEnabled')->default(false);
            $table->boolean('ticketSalesEnabled')->default(true);
            $table->boolean('ticketSeasonsEnabled')->default(false);
            $table->boolean('ticketVouchersEnabled')->default(false);
            $table->boolean('reportsEnabled')->default(false);
            $table->boolean('printTicketSeasonEnabled')->default(false);
            $table->boolean('labelsEnabled')->default(false);
            $table->boolean('printLabelsEnabled')->default(false);
            $table->boolean('invitationsEnabled')->default(false);
            $table->boolean('hardTicketEnabled')->default(false);
            $table->boolean('homeTicketEnabled')->default(false);
            $table->string('clientHomeTicketId')->nullable();
            // SMS
            $table->boolean('smsEnabled')->default(false);
            $table->string('smsUser')->nullable();
            $table->string('smsPassword', 60)->nullable();
            // TPV
            $table->integer('tpvCommerce')->nullable();
            $table->smallInteger('tpvTerminal')->nullable();
            $table->string('tpvKey')->nullable();
            $table->string('tpvPort')->nullable();
            $table->string('tpvVersion')->nullable();
            // Comisiones y liquidaciones
            $table->enum('liquidationPeriodicity', ['annual', 'biannual', 'quarterly', 'monthly'])->default('annual');
            $table->dateTime('nextLiquidationEndDate')->nullable();

            $table->decimal('saleCommissionPercentage', 8, 2)->nullable();
            $table->decimal('saleCommissionMinimum', 8, 2)->nullable();
            $table->decimal('saleCommissionMaximum', 8, 2)->nullable();
            $table->decimal('shippingCommissionPercentage', 8, 2)->nullable();
            $table->decimal('shippingCommissionMinimum', 8, 2)->nullable();
            $table->decimal('shippingCommissionMaximum', 8, 2)->nullable();
            $table->decimal('printCommissionPercentage', 8, 2)->nullable();
            $table->decimal('printCommissionMinimum', 8, 2)->nullable();
            $table->decimal('printCommissionMaximum', 8, 2)->nullable();
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
        Schema::dropIfExists('PointOfSale');
    }
}
