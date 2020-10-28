<?php

use App\User;
use Illuminate\Database\Seeder;
use Savitar\Auth\SavitarRole;
use Savitar\Auth\SavitarUser;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        config(['savitar_auth.confirm_mail_enabled_aux' => false]);
        $this->create('cliente@redentradas.es', 'Cliente', 'cliente', 'cliente1');
        $this->create('administrador@redentradas.es', 'Administrador', 'administrador');
        $this->create('promotor@redentradas.es', 'Promotor', 'promotor', 'promotor1');
        $this->create('punto-de-venta@redentradas.es', 'Punto de venta', 'punto-de-venta');
        $this->create('accesos@redentradas.es', 'Usuario de control de accesos', 'control-de-accesos');
        config(['savitar_auth.confirm_mail_enabled_aux' => true]);
    }

    /**
     * @param $email
     * @param $name
     * @param $roleSlug
     * @param string|null $password
     */
    private function create($email, $name, $roleSlug, ?string $password = null): void
    {
        /** @var User $user */
        $user = SavitarUser::where('email', $email)->firstOrNew([]);
        $user->role()->associate(SavitarRole::where('slug', $roleSlug)->first());
        $user->email = $email;
        $user->name = $name;
        if ($password) {
            $user->password = bcrypt($password);
        } else {
            $user->password = bcrypt(\Illuminate\Support\Str::slug($name));
        }
        $user->emailConfirmed = true;
        $user->save();
    }
}
