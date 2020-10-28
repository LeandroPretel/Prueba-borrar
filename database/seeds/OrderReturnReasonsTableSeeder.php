<?php

use App\OrderReturnReason;
use Illuminate\Database\Seeder;

class OrderReturnReasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->create('Error del vendedor');
        $this->create('Error del cliente');
        $this->create('Incidencia técnica');
        $this->create('Petición del promotor');
        $this->create('Cambios en abonos');
        $this->create('Cancelación');
        $this->create('Cambio de fecha');
        $this->create('Otro');
    }

    /**
     * Creates a new OrderReturnReason and saves it
     * @param $name
     */
    private function create(string $name): void
    {
        $orderReturnReason = new OrderReturnReason();
        $orderReturnReason->name = $name;
        $orderReturnReason->save();
    }
}
