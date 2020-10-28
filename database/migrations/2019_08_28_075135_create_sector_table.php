<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateSectorTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Sector', function (Blueprint $table) {
            $this->createModelColumns($table);
            $this->createBelongsToColumn($table, 'Area');

            $table->unsignedInteger('order')->default(1);
            $table->string('name');
            $table->string('webName');
            $table->string('ticketName');
            $table->boolean('isNumbered')->default(false);
            $table->integer('totalSeats')->default(0);
            $table->integer('rows')->nullable(); // may be null if isNumbered = false
            $table->integer('columns')->nullable(); // may be null if isNumbered = false
            $table->boolean('disabledAccess')->default(false);
            $table->boolean('reducedVisibility')->default(false);
            $table->decimal('stageLocation')->nullable(); //  orientating values = [-22.5, 0, 45, 90, 112.5, -45, 135, -67.5, 270, 225, 180, 157.5]; // angles set from arrow-bottom-right
            $table->json('points');
            $table->json('centers');
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
        Schema::dropIfExists('Sector');
    }
}
