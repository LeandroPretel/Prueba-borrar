<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreatePaymentAttemptTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('PaymentAttempt', function (Blueprint $table) {
            $this->createModelColumns($table, false);
            $this->createBelongsToColumn($table, 'Order');

            $table->enum('status', ['attempt', 'successful', 'failed'])->default('attempt');
            $table->decimal('amount', 12, 2)->default(0);
            $table->enum('paymentMethod', ['card', 'cash'])->default('card');
            $table->string('authorizationCode')->nullable();
            $table->unsignedInteger('redsysNumber')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('PaymentAttempt');
    }
}
