<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateConsultationTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Consultation', function (Blueprint $table) {
            $this->createModelColumns($table);
            $table->string('fullName');
            $table->string('email');
            $table->string('phone');
            $table->string('consultationReason');
            $table->string('consultationText');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Consultation');
    }
}
