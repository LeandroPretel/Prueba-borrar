<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreatePlaceTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Place', function (Blueprint $table) {
            $this->createModelColumns($table);

            $zoneTableName = config('savitar_auth.zones.table', 'Zone');
            $table->uuid('countryId')->nullable();
            $table->foreign('countryId', 'countryId')
                ->references('id')->on($zoneTableName)
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->uuid('provinceId')->nullable();
            $table->foreign('provinceId', 'provinceId')
                ->references('id')->on($zoneTableName)
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('name');
            $table->string('webName');
            $table->string('ticketName');
            $table->text('bannerText')->nullable();
            $table->string('bannerTextColor')->nullable();
            $table->boolean('bannerTextIsVisible')->default(false);
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('zipCode')->nullable();
            $table->text('description')->nullable();
            $table->string('mapLink')->nullable();
            $table->boolean('hasAccessControl')->default(true);
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
        Schema::dropIfExists('Place');
    }
}
