<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Savitar\Models\Traits\Migrable;

class CreateBrandTable extends Migration
{
    use Migrable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable('Brand')) {
            Schema::create('Brand', function (Blueprint $table) {
                $this->createModelColumns($table);

                $table->string("name");
                $table->string("domain")->unique()->nullable();
                $table->string("selectedTheme")->default("bee-redentradas-theme");
                $table->text('observations')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('Brand');
    }
}
