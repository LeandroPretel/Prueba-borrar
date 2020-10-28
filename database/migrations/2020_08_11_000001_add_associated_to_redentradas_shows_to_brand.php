<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class AddAssociatedToRedentradasShowsToBrand extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('Brand', static function (Blueprint $table) {
            $table->boolean('associatedToRedentradasShows')->default(false);
        });

        Schema::table('BrandShow', static function (Blueprint $table) {
            $table->boolean('associatedToRedentradas')->default(false);
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
