<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateInvoiceNumberTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('InvoiceNumber', function (Blueprint $table) {
            $this->createModelColumns($table);

            $table->unsignedInteger('number')->default(1);
            // $table->enum('type', ['recharge', 'invoice'])->default('recharge');
            $table->unsignedInteger('yearNumber');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('InvoiceNumber');
    }
}
