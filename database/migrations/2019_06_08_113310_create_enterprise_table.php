<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateEnterpriseTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Enterprise', function (Blueprint $table) {
            $this->createModelColumns($table);
            if (config('savitar_auth.zones.enabled')) {
                $table->uuid('countryId')->nullable();
                $table->foreign('countryId', 'countryId')
                    ->references('id')->on(config('savitar_auth.zones.table', 'Zone'))
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->uuid('provinceId');
                $table->foreign('provinceId', 'provinceId')
                    ->references('id')->on(config('savitar_auth.zones.table', 'Zone'))
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }

            $table->string('name');
            $table->string('socialReason');
            $table->string('nif');
            $table->string('city');
            $table->string('address');
            $table->string('zipCode');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('chargeToAccount')->default(false);
            $table->boolean('requireMinCommission')->default(false);
            $table->decimal('minCommission', 8, 2)->nullable();
            $table->string('chargeIban')->nullable();
            $table->string('paymentIban')->nullable();
            $table->string('contactName')->nullable();
            $table->string('contactNif')->nullable();
            $table->string('contactEmail')->nullable();
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
        Schema::dropIfExists('Enterprise');
    }
}
