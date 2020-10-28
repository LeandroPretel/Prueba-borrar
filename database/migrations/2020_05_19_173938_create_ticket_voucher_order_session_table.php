<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateTicketVoucherOrderSessionTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('TicketVoucherOrderSession', function (Blueprint $table) {
            $this->createModelColumns($table);
            $this->createBelongsToColumn($table, 'TicketVoucherOrder');
            $this->createBelongsToColumn($table, 'Session');

            $table->unique(['ticketVoucherOrderId', 'sessionId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('TicketVoucherOrderSession');
    }
}
