<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateDiscountTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Discount', function (Blueprint $table) {
            $this->createModelColumns($table);
            $table->enum('type', ['discount', 'promotion'])->default('discount');
            $table->boolean('isActive')->default(true);
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('isPercentage')->default(true);
            $table->decimal('amount', 8, 2)->default(0);
            $table->decimal('minAmountToUse', 8, 2)->nullable();
            $table->decimal('maxAmountToUse', 8, 2)->nullable();
            $table->unsignedInteger('timesUsed')->default(0);
            $table->unsignedInteger('maximumUses')->nullable();
            $table->dateTime('startDate')->nullable();
            $table->dateTime('endDate')->nullable();
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
        Schema::dropIfExists('Discount');
    }
}
