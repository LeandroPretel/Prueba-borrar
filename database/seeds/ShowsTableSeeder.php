<?php

use App\Account;
use App\Show;
use Illuminate\Database\Seeder;

class ShowsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $account = Account::where('email', 'promotor@redentradas.es')->first();

        for($i = 0; $i < 200; $i++) {
            $show = new Show();
            $show->observations = 'Evento de prueba '. $i;
            $show->account()->associate($account);
            $show->save();
        }
        /*
        $show = new Show();
        $show->observations = 'Evento de prueba 1';
        $show->account()->associate($account);
        $show->save();

        $show = new Show();
        $show->observations = 'Evento de prueba 2';
        $show->account()->associate($account);
        $show->save();

        $show = new Show();
        $show->observations = 'Evento de prueba 3';
        $show->account()->associate($account);
        $show->save();

        $show = new Show();
        $show->observations = 'Evento de prueba 4';
        $show->account()->associate($account);
        $show->save();

        $show = new Show();
        $show->observations = 'Evento de prueba 5';
        $show->account()->associate($account);
        $show->save();
        */
    }
}
