<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateShowCategoryTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('ShowCategory', function (Blueprint $table) {
            $this->createModelColumns($table);
            $table->string('name');
            $table->decimal('vat', 6, 2)->default(0.00);
            $table->decimal('sgaeFee', 6, 2)->default(0.00);
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
        Schema::dropIfExists('ShowCategory');
    }
}
