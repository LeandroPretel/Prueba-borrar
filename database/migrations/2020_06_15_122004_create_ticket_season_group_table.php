<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateTicketSeasonGroupTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('TicketSeasonGroup', function (Blueprint $table) {
            $this->createModelColumns($table);

            $table->string('name');
            $table->text('observations')->nullable();
        });

        Schema::table('TicketSeason', function (Blueprint $table) {
            $this->createBelongsToColumn($table, 'TicketSeasonGroup', true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('TicketSeasonGroup');
    }
}
