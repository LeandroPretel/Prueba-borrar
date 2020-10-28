<?php

use App\Discount;
use Illuminate\Database\Seeder;

class DiscountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $discount = new Discount();
        $discount->type = 'discount';
        $discount->name = 'Descuento de prueba';
        $discount->amount = 5;
        $discount->code = 'test';
        $discount->save();


        $discount = new Discount();
        $discount->type = 'promotion';
        $discount->name = 'Promoción de prueba';
        $discount->amount = 2;
        $discount->save();
    }
}
