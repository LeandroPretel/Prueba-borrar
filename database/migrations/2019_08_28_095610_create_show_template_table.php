<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateShowTemplateTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('ShowTemplate', function (Blueprint $table) {
            $this->createModelColumns($table);
            $this->createBelongsToColumn($table, 'ShowClassification', true);

            $table->string('name');
            $table->string('webName');
            $table->string('ticketName');
            $table->string('slug')->index();
            $table->text('description')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('break')->nullable();
            $table->text('additionalInfo')->nullable();
            $table->string('videoId')->nullable();
            $table->boolean('hasPassword')->default(false);
            $table->string('password', 60)->nullable();
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
        Schema::dropIfExists('ShowTemplate');
    }
}
