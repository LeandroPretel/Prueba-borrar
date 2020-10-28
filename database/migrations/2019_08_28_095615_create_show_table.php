<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateShowTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Show', function (Blueprint $table) {
            $this->createModelColumns($table);
            $this->createAccountsRelation($table);

            $table->string('name')->nullable();
            $table->string('webName')->nullable();
            $table->string('ticketName')->nullable();
            $table->string('slug')->index()->nullable();
            $table->text('description')->nullable();
            $table->boolean('isFeatured')->default(false);
            $table->text('featuredText')->nullable();
            $table->boolean('associatedToTuPalacio')->default(false);
            $table->string('videoId')->nullable();
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
        Schema::dropIfExists('Show');
    }
}
