<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class AddClientToOrderReturn extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('OrderReturn', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('OrderReturn', 'clientId')) {
                $this->createBelongsToColumn($table, 'Client', true);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
    }
}
