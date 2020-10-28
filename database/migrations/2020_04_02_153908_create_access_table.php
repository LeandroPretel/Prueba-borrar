<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateAccessTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Access', function (Blueprint $table) {
            $this->createModelColumns($table, false);
            $this->createBelongsToColumn($table, 'Session');
            $this->createBelongsToColumn($table, 'Ticket');
            $this->createBelongsToColumn($table, 'SessionSeat');
            $this->createBelongsToColumn($table, 'Client', true);

            $table->enum('status', ['error', 'successful', 'out'])->default('successful');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('Access');
    }
}
