<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateBrandShowTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('BrandShow', function (Blueprint $table) {
            $this->createModelColumns($table, false);
            $this->createBelongsToColumn($table, 'Brand');
            $this->createBelongsToColumn($table, 'Show');

            $table->unique(['brandId', 'showId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('BrandShow');
    }
}
