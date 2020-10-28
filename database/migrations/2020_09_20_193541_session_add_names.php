<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SessionAddNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Session', function (Blueprint $table) {
            $table->string('webName', 255)->nullable();
            $table->string('ticketName', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Session', function (Blueprint $table) {
            $table->dropColumn('webName');
            $table->dropColumn('ticketName');
        });
    }
}
