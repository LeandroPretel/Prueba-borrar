<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateTicketSeasonTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('TicketSeason', function (Blueprint $table) {
            $this->createModelColumns($table);
            $this->createAccountsRelation($table);
            $this->createBelongsToColumn($table, 'Place');
            $this->createBelongsToColumn($table, 'Space');

            $table->string('name');
            $table->string('webName');
            $table->string('ticketName');
            $table->boolean('isActive')->default(false);
            $table->enum('type', ['fixed', 'combined'])->default('fixed');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('minSessions')->nullable();
            $table->unsignedSmallInteger('maxSessions')->nullable();
            $table->boolean('renovationsEnabled')->default(false);
            $table->dateTime('renovationStartDate')->nullable();
            $table->dateTime('renovationEndDate')->nullable();
            $table->boolean('changesEnabled')->default(false);
            $table->dateTime('changesStartDate')->nullable();
            $table->dateTime('changesEndDate')->nullable();
            $table->boolean('newSalesEnabled')->default(false);
            $table->dateTime('newSalesStartDate')->nullable();
            $table->dateTime('newSalesEndDate')->nullable();
            $table->boolean('shippingEnabled')->default(false);
            $table->dateTime('shippingStartDate')->nullable();
            $table->dateTime('shippingEndDate')->nullable();
            $table->boolean('printingEnabled')->default(false);
            $table->text('observations')->nullable();
        });

        Schema::table('Fare', function (Blueprint $table) {
            $this->createBelongsToColumn($table, 'TicketSeason', true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('Fare', static function (Blueprint $table) {
            $table->dropForeign(['ticketSeasonId']);
            $table->dropColumn('ticketSeasonId');
        });

        Schema::dropIfExists('TicketSeason');
    }
}
