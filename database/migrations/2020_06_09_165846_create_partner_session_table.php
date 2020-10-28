<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreatePartnerSessionTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('PartnerSession', function (Blueprint $table) {
            $this->createModelColumns($table, false);
            $this->createBelongsToColumn($table, 'Partner');
            $this->createBelongsToColumn($table, 'Session');

            $table->decimal('commissionPercentage', 8, 2)->nullable();
            $table->decimal('commissionMinimum', 8, 2)->nullable();
            $table->decimal('commissionMaximum', 8, 2)->nullable();

            $table->unique(['partnerId', 'sessionId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('PartnerSession');
    }
}
