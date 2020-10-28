<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateSessionAreaTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('SessionArea', function (Blueprint $table) {
            $this->createModelColumns($table);
            $this->createBelongsToColumn($table, 'Session');
            $this->createBelongsToColumn($table, 'Space');

            $table->unsignedInteger('order')->default(1);
            $table->string('name');
            $table->string('webName');
            $table->string('ticketName');
            $table->string('color');
            $table->integer('totalSeats')->default(0);
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
        Schema::dropIfExists('SessionArea');
    }
}
