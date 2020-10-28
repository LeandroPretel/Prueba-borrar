<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreatePartnerTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Partner', function (Blueprint $table) {
            $this->createModelColumns($table);

            $table->string('name');
            $table->boolean('isActive')->default(true);
            $table->dateTime('liquidationUntilDate')->nullable();
            $table->decimal('commissionPercentage', 8, 2)->nullable();
            $table->decimal('commissionMinimum', 8, 2)->nullable();
            $table->decimal('commissionMaximum', 8, 2)->nullable();
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
        Schema::dropIfExists('Partner');
    }
}
