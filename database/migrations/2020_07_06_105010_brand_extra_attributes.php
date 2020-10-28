<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BrandExtraAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('Brand', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->string('facebookUrl')->nullable();
            $table->string('twitterUrl')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('zipCode')->nullable();
            $table->uuid('provinceId')->nullable();
            $table->foreign('provinceId', 'provinceId')
                ->references('id')->on('Zone')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
}
