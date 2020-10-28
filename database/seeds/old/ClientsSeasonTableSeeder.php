<?php

namespace old;

use Carbon\Carbon;
use DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Savitar\Auth\SavitarRole;

class ClientsSeasonTableSeeder extends OldTableSeeder
{
    protected $tableName = 'abonados';
    protected $defaultOrder = 'id_abonado';
    protected $roleId;
    // protected $maxInserts = 50000;

    private $users;
    private $spainId;
    private $zones;
    private $ticketSeasonGroups;

    public function prepareRecords(): void
    {
        $this->roleId = SavitarRole::where('slug', 'cliente')->first()->id;

        $this->users = [];
        $usersResult = DB::table('User')->select('id', 'oldId')->get();
        foreach ($usersResult as $result) {
            $this->users[$result->oldId] = $result->id;
        }
        $usersResult = null;

        $this->zones = [];
        $zonesResult = DB::table('Zone')->select('id', 'slug')->get();
        foreach ($zonesResult as $result) {
            $this->zones[$result->slug] = $result->id;
        }
        $zonesResult = null;
        $this->spainId = $this->zones['espana'];

        $this->ticketSeasonGroups = [];
        $ticketSeansonGroupsResult = DB::table('TicketSeasonGroup')->select('id', 'oldId')->get();
        foreach ($ticketSeansonGroupsResult as $result) {
            $this->ticketSeasonGroups[$result->oldId] = $result->id;
        }
        $ticketSeansonGroupsResult = null;
    }

    public function run(): void
    {
        $this->massiveImport();
    }

    /**
     * @param $results
     */
    public function createRecords($results): void
    {
        $clientsToCreate = [];
        $usersToCreate = [];
        $clientTicketSeasonGroupsToCreate = [];
        $timestamp = Carbon::now()->toISOString();

        foreach ($results as $result) {
            if (!isset($users[$result->id_abonado])) {
                $fullName = explode(" ", $result->nombre);
                $userId = Uuid::uuid4()->toString();
                $user = [
                    "id" => $userId,
                    "accountId" => null,
                    "roleId" => $this->roleId,
                    "email" => $result->email,
                    "name" => $fullName[0],
                    "password" => 'test', // TODO: PASSWORD REAL?
                    "isFirstLogin" => true,
                    "isActive" => true,
                    "emailConfirmed" => true,
                    "canReceiveNotifications" => true,
                    "canReceiveEmails" => true,
                    "createdAt" => $timestamp,
                    "updatedAt" => $timestamp,
                    "oldId" => $result->id_abonado
                ];

                if ($result->fecha_alta_utc) {
                    $user["createdAt"] = Carbon::parse($result->fecha_alta_utc)->toISOString();
                }

                $clientId = Uuid::uuid4()->toString();
                $name = $fullName[0];
                unset($fullName[0]);
                $provinceId = $this->zones[Str::slug(Str::lower($result->provincia))] ?? null;
                if (Str::slug(Str::lower($result->provincia)) === "granda") {
                    $provinceId = $this->zones["granada"];
                }
                $client = [
                    "id" => $clientId,
                    "createdAt" => $user["createdAt"],
                    "updatedAt" => $timestamp,
                    "userId" => $userId,
                    "countryId" => $this->spainId,
                    "provinceId" => $provinceId,
                    "name" => $name,
                    "surname" => implode(' ', $fullName),
                    "nif" => $result->nif,
                    "fax" => $result->fax,
                    "phone" => $result->telefono,
                    "address" => implode(' ', [$result->domicilio, $result->domicilio2]),
                    "zipCode" => $result->cpostal,
                    "city" => $result->dmun50,
                    "preferHomeDelivery" => $result->envio_domicilio_preferido,
                    "associatedToTuPalacio" => false,
                    "isSeasonClient" => true,
                    "observations" => implode(' ', [$result->observaciones, $result->obs1, $result->obs2, $result->obs3]),
                    "oldId" => $result->id_abonado,
                ];

                $clientTicketSeasonGroupId = Uuid::uuid4()->toString();
                $clientTicketSeasonGroup = [
                    "id" => $clientTicketSeasonGroupId,
                    "createdAt" => $timestamp,
                    "updatedAt" => $timestamp,
                    "clientId" => $clientId,
                    "ticketSeasonGroupId" => $this->ticketSeasonGroups[$result->id_grupo_abonados]
                ];
                $usersToCreate[] = $user;
                $clientTicketSeasonGroupsToCreate[] = $clientTicketSeasonGroup;
                $clientsToCreate[] = $client;
            }
        }

        $this->chunkInsert('User', 2000, $usersToCreate);
        $this->chunkInsert('Client', 2000, $clientsToCreate);
        $this->chunkInsert('ClientTicketSeasonGroup', 2000, $clientTicketSeasonGroupsToCreate);
    }
}
