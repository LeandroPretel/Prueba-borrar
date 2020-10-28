<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateTicketVoucherPointOfSaleTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('TicketVoucherPointOfSale', function (Blueprint $table) {
            $this->createModelColumns($table, false);
            $this->createBelongsToColumn($table, 'TicketVoucher');
            $this->createBelongsToColumn($table, 'PointOfSale');

            $table->unique(['ticketVoucherId', 'pointOfSaleId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('TicketVoucherPointOfSale');
    }
}
