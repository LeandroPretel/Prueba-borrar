<?php

namespace old;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class UserCypherSeeder extends Seeder
{
    protected $tableName = 'clientes';
    protected $defaultOrder = 'id_cliente';
    protected $role;
    /** @var Collection */
    private $users;

    public function createRecords(): void
    {
        $users = User::get();
        foreach ($users as $user) {
            $user->password = bcrypt($user->password);
            $user->save();
        }
    }
}
