<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateEnterprisableTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Enterprisable', function (Blueprint $table) {
            $this->createBelongsToColumn($table, 'Enterprise');
            $table->uuid('enterprisableId')->nullable();
            $table->string('enterprisableType')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('Enterprisable');
    }
}
