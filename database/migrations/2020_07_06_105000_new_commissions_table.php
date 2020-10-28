<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Session', function (Blueprint $table) {
            $table->decimal('hardTicketPrintCommissionPercentage', 8, 2)->nullable();
            $table->decimal('hardTicketPrintCommissionMinimum', 8, 2)->nullable();
            $table->decimal('hardTicketPrintCommissionMaximum', 8, 2)->nullable();

            $table->decimal('invitationCommissionPercentage', 8, 2)->nullable();
            $table->decimal('invitationCommissionMinimum', 8, 2)->nullable();
            $table->decimal('invitationCommissionMaximum', 8, 2)->nullable();
        });
    }
}
