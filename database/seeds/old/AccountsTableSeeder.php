<?php

namespace old;

use App\Account;
use App\Enterprise;
use App\User;
use DB;
use Savitar\Auth\SavitarRole;
use Savitar\Auth\SavitarUser;

class AccountsTableSeeder extends OldTableSeeder
{
    protected $tableName = 'organizadores';
    protected $defaultOrder = 'id_organizador';

    private $enterprises;
    private $users;

    public function prepareRecords(): void
    {
        $this->enterprises = [];
        $enterprisesResults = DB::table("Enterprise")->select('id', 'oldId')->get();
        foreach ($enterprisesResults as $enterprisesResult) {
            $this->enterprises[$enterprisesResult->oldId] = $enterprisesResult->id;
        }
        $enterprisesResult = null;

        $this->users = [];
        $usersResults = DB::table('User')->select('id', 'oldId')->get();
        foreach ($usersResults as $usersResult) {
            $this->users[$usersResult->oldId] = $usersResult->id;
        }
        $usersResults = null;
    }

    /**
     * @param $result
     */
    public function createRecords($result): void
    {
        /*$accountsToCreate = [];
        $enterprisesToCreate = [];
        $usersToUpdate = [];
        $timestamp = Carbon::now()->toIso8601String();

        foreach($results as $result){
            $accountId = Uuid::uuid4()->toString();
            $account = [
                "id" => $accountId,
                "countryId" => "",
                "provinceId" => "",
                "name" => "",
                "slug" => "",
                "nif" => "",
                "isActive" => "",
                "city" => "",
                "address" => "",
                "zipCode" => "",
                "phone" => "",
                "fax" => "",
                "website" => "",
                "email" => "",
                "facebookUrl" => "",
                "twitterUrl" => "",
                "youtubeUrl" => "",
                "instagramUrl" => "",
                "selectedTheme" => "",
                "initialConfiguration" => "",
                "isSuperAdminAccount" => "",
                "observations" => "",
                "contactName" => "",
                "contactPhone" => "",
                "maximumAdvance" => "",
                "canCreateInvitations" => "",
                "unlimitedInvitations" => "",
                "createdBy" => "",
                "updatedBy" => "",
                "deletedBy" => "",
                "createdAt" => "",
                "updatedAt" => "",
                "deletedAt" => "",
                "anfixCustomerId" => "",
                "anfixCompanyAccountingAccountNumber" => "",
                "oldId" => "",
            ];

            $user = [
                "id"=>"",
                "email" => "",
                "roleId" => $this->roleId,
                "password" => $result->password,
                "emailConfirmed" => true,
                "accountId" => $accountId
            ];
        }
        */
        /** @var Enterprise $enterprise */
        $enterprise = Enterprise::where('oldId', $result->id_empresa)->first();
        if ($enterprise) {
            $account = new Account();
            $account->name = $result->nombre_entrada;
            $account->phone = $result->fact_telefono;
            $account->email = $result->fact_email;
            $account->maximumAdvance = $result->anticipo_maximo;
            $account->canCreateInvitations = $result->emitir_invitaciones;
            $account->unlimitedInvitations = $result->invitaciones_ilimitadas;
            $account->oldId = $result->id_organizador;
            $account->save();
            $account->enterprises()->sync($enterprise);
            config(['savitar_auth.confirm_mail_enabled_aux' => false]);
            /** @var User $user */
            $user = SavitarUser::where('email', $result->fact_email)->firstOrNew([]);
            $user->account()->associate($account);
            $user->role()->associate(SavitarRole::where('slug', 'promotor')->first());
            $user->email = $enterprise->email;
            $user->name = $result->nombre_entrada;
            //$user->password = bcrypt($result->password);
            $user->password = $result->password;
            $user->emailConfirmed = true;
            $user->save();
            config(['savitar_auth.confirm_mail_enabled_aux' => true]);
        }
    }

    /*public function run():void
    {
        $this->massiveImport();
    }*/
}
