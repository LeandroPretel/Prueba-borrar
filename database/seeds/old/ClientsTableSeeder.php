<?php

namespace old;

use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Savitar\Auth\SavitarRole;
use Savitar\Auth\SavitarZone;

class ClientsTableSeeder extends OldTableSeeder
{
    protected $tableName = 'clientes';
    protected $defaultOrder = 'id_cliente';
    protected $role;
    /** @var Collection */
    private $users;
    private $spainId;

    // protected $maxInserts = 50000;

    public function prepareRecords(): void
    {
        $this->role = SavitarRole::where('slug', 'cliente')->first();
        $this->users = DB::table("User")->select('id')->get();
        $this->spainId = SavitarZone::where('name', 'España')->first()->id;
    }

    /**
     * @param $results
     */
    public function createRecords($results): void
    {
        $timestamp = Carbon::now()->toISOString();

        $usersToCreate = [];
        $clientsToCreate = [];

        foreach ($results as $result) {
            $userId = Uuid::uuid4()->toString();
            $user = [
                "id" => $userId,
                "accountId" => null,
                "roleId" => $this->role->id,
                "email" => $result->email,
                "name" => $result->nombre,
                "socialNickname" => null,
                "socialAvatar" => null,
                "password" => $result->password,
                "isFirstLogin" => true,
                "isActive" => $result->activo,
                "emailConfirmed" => true,
                "canReceiveNotifications" => true,
                "canReceiveEmails" => $result->enviar_correo,
                "observations" => null,
                "createdBy" => null,
                "updatedBy" => null,
                "deletedBy" => null,
                "createdAt" => Carbon::parse($result->fecha_alta),
                "updatedAt" => $timestamp,
                "deletedAt" => null,
                "oldId" => $result->id_cliente
            ];

            $clientId = Uuid::uuid4()->toString();
            $client = [
                "id" => $clientId,
                "createdAt" => Carbon::parse($result->fecha_alta),
                "updatedAt" => $timestamp,
                "deletedAt" => null,
                "createdBy" => null,
                "updatedBy" => null,
                "deletedBy" => null,
                "userId" => $userId,
                "countryId" => $this->spainId,
                "provinceId" => null,
                "name" => $result->nombre,
                "surname" => $result->apellidos,
                "nif" => $result->nif,
                "phone" => $result->telefono,
                "fax" => null,
                "birthDate" => null,
                "city" => null,
                "address" => null,
                "zipCode" => null,
                "preferHomeDelivery" => false,
                "associatedToTuPalacio" => false,
                "isSeasonClient" => false,
                "observations" => null,
                "oldId" => $result->id_cliente,
            ];
            $usersToCreate[] = $user;
            $clientsToCreate[] = $client;
        }

        $this->chunkInsert('User', 500, $usersToCreate);
        $usersToCreate = null;
        $this->chunkInsert('Client', 500, $clientsToCreate);
        $clientsToCreate = null;
    }

    public function run(): void
    {
        $this->massiveImport(10000);
    }
}
