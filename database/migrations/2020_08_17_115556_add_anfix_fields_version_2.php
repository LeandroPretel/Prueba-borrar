<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnfixFieldsVersion2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('Account', static function (Blueprint $table) {
            if (Schema::hasColumn('Account', 'anfixCustomerId') && Schema::hasColumn('Account', 'anfixCompanyAccountingAccountNumber')) {
                $table->dropColumn(['anfixCustomerId', 'anfixCompanyAccountingAccountNumber']);
            }
        });

        // Cuenta caja para los PV
        Schema::table('PointOfSale', static function (Blueprint $table) {
            if (!Schema::hasColumn('PointOfSale', 'anfixCompanyAccountingAccountNumber')) {
                $table->integer('anfixCompanyAccountingAccountNumber')->nullable();
            }
        });

        // Cuentas para la empresa (cliente, proveedor y promotor)
        Schema::table('Enterprise', static function (Blueprint $table) {
            // Cliente Anfix (Customer)
            if (!Schema::hasColumn('Enterprise', 'anfixCustomerId')) {
                $table->string('anfixCustomerId')->nullable();
            }
            if (!Schema::hasColumn('Enterprise', 'anfixCustomerId')) {
                $table->integer('anfixCustomerCompanyAccountingAccountNumber')->nullable();
            }
            // Proveedor Anfix (Supplier)
            if (!Schema::hasColumn('Enterprise', 'anfixCustomerId')) {
                $table->string('anfixSupplierId')->nullable();
            }
            if (!Schema::hasColumn('Enterprise', 'anfixCustomerId')) {
                $table->integer('anfixSupplierCompanyAccountingAccountNumber')->nullable();
            }
            // Cuenta para promotor
            if (!Schema::hasColumn('Enterprise', 'anfixCompanyAccountingAccountNumber')) {
                $table->integer('anfixCompanyAccountingAccountNumber')->nullable();
            }
        });

        Schema::table('Ticket', static function (Blueprint $table) {
            // Entrada(asiento) contable Anfix para devolución
            if (!Schema::hasColumn('Ticket', 'returnAnfixAccountingEntryId')) {
                $table->string('returnAnfixAccountingEntryId')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        //
    }
}
