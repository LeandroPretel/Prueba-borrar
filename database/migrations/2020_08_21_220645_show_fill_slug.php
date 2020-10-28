<?php

use App\Http\Controllers\ShowController;
use App\Show;
use Illuminate\Database\Migrations\Migration;

class ShowFillSlug extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up(): void
    {
        ShowController::fillShowSlugs();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        //
    }
}
