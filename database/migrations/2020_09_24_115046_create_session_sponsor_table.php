<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateSessionSponsorTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('SessionSponsor', function (Blueprint $table) {
            $this->createModelColumns($table, false);
            $this->createBelongsToColumn($table, 'Session');
            $this->createBelongsToColumn($table, 'Sponsor');

            $table->unique(['sessionId', 'sponsorId']);
        });

        Schema::table('SessionPrintModel', static function (Blueprint $table) {
            $table->boolean('showSponsorsOnHomeTicket')->default(true);
            $table->boolean('showSponsorsOnHardTicket')->default(true);
            $table->boolean('showSponsorsOnInvitations')->default(true);
            $table->boolean('showSponsorsOnSeasons')->default(true);
            $table->boolean('showSponsorsOnVouchers')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('SessionSponsor');
    }
}
