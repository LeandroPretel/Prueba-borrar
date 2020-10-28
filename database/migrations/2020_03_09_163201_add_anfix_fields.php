<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnfixFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('Account', static function (Blueprint $table) {
            // Cliente Anfix
            $table->string('anfixCustomerId')->nullable();
            $table->string('anfixCompanyAccountingAccountNumber')->nullable();
        });
        Schema::table('Ticket', static function (Blueprint $table) {
            // Asiento contable Anfix
            $table->string('anfixAccountingEntryId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
