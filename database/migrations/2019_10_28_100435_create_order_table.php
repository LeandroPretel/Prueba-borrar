<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateOrderTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Order', function (Blueprint $table) {
            $this->createModelColumns($table);
            $this->createAccountsRelation($table);
            $this->createBelongsToColumn($table, 'PointOfSale');
            $this->createBelongsToColumn($table, 'Client', true);
            $this->createBelongsToColumn($table, 'Discount', true);

            $table->string('locator', 8)->index();
            $table->enum('type', ['normal', 'season', 'voucher', 'hard-ticket', 'home-ticket', 'invitation'])->default('normal')->index();
            $table->enum('via', ['web', 'automatic', 'assisted', 'phone'])->default('web');
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('amountPaid', 12, 2)->default(0);
            $table->decimal('amountPending', 12, 2)->default(0);
            $table->enum('status', ['pending', 'paid', 'partially-paid', 'unfinished'])->default('pending');
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
        Schema::dropIfExists('Order');
    }
}
