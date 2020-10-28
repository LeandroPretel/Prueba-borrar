<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreatePointOfSaleSessionTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('PointOfSaleSession', function (Blueprint $table) {
            $this->createModelColumns($table, false);
            $this->createBelongsToColumn($table, 'PointOfSale');
            $this->createBelongsToColumn($table, 'Session');

            $table->unique(['pointOfSaleId', 'sessionId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('PointOfSaleSession');
    }
}
