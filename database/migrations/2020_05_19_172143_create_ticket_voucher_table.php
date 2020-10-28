<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateTicketVoucherTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('TicketVoucher', function (Blueprint $table) {
            $this->createModelColumns($table);
            $this->createAccountsRelation($table);

            $table->string('name');
            $table->string('webName');
            $table->string('ticketName');
            $table->boolean('isActive')->default(false);
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('minSessions')->nullable();
            $table->unsignedSmallInteger('maxSessions')->nullable();
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
        Schema::dropIfExists('TicketVoucher');
    }
}
