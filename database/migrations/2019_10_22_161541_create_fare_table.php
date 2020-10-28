<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateFareTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Fare', function (Blueprint $table) {
            $this->createModelColumns($table);
            $this->createBelongsToColumn($table, 'Session');

            $table->string('name');
            $table->string('webName');
            $table->string('ticketName');
            $table->text('description')->nullable();
            $table->text('webDescription')->nullable();
            $table->boolean('checkIdentity')->default(false);
            $table->text('checkIdentityMessage')->nullable();
            $table->text('assistedPointOfSaleMessage')->nullable();
            $table->unsignedInteger('minTicketsByOrder')->nullable();
            $table->unsignedInteger('maxTicketsByOrder')->nullable();
            $table->unsignedInteger('maxTickets')->nullable();
            $table->dateTime('restrictionStartDate')->nullable();
            $table->dateTime('restrictionEndDate')->nullable();
            $table->boolean('associatedToTuPalacio')->default(false);
            $table->boolean('isPromotion')->default(false);
            $table->boolean('isSeason')->default(false);
            $table->text('observations')->nullable();
        });

        Schema::table('Session', static function (Blueprint $table) {
            $table->uuid('defaultFareId')->nullable();
            $table->foreign('defaultFareId')
                ->references('id')
                ->on('Fare')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('Fare');
        Schema::table('Session', static function (Blueprint $table) {
            $table->dropForeign(['defaultFareId']);
            $table->dropColumn('defaultFareId');
        });
    }
}
