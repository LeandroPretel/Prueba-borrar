<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateClientTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Client', function (Blueprint $table) {
            $zoneTableName = config('savitar_auth.zones.table', 'Zone');
            $this->createModelColumns($table);
            $this->createBelongsToColumn($table, 'User');

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
            $table->string('surname');
            $table->string('nif');
            $table->string('phone');
            $table->string('fax')->nullable();
            $table->timestamp('birthDate')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('zipCode')->nullable();
            $table->boolean('preferHomeDelivery')->default(false);
            $table->boolean('associatedToTuPalacio')->default(false);
            $table->boolean('isSeasonClient')->default(false);
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
        Schema::dropIfExists('Client');
    }
}
