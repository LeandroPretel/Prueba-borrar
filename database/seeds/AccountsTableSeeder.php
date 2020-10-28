<?php

use App\Account;
use App\User;
use Illuminate\Database\Seeder;
use Savitar\Auth\SavitarBillingData;
use Savitar\Auth\SavitarZone;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $account = new Account();
        $account->name = 'Redentradas';
        $account->phone = '958 10 81 81';
        $account->email = 'promotor@redentradas.es';
        $account->address = 'C/ Ancha de la Virgen, 27';
        $account->city = 'Granada';
        $account->zipCode = '18009';
        $account->nif = 'B18989871';
        $account->province()->associate(SavitarZone::whereName('Granada')->first());
        $account->save();

        /** @var User $user */
        $user = User::where('email', 'promotor@redentradas.es')->first();
        if ($user) {
            $user->account()->associate($account);
            $user->save();
        }

        $billingData = new SavitarBillingData();
        $billingData->account()->associate($account);
        $billingData->name = $account->name;
        $billingData->nif = $account->nif;
        $billingData->city = $account->city;
        $billingData->address = $account->address;
        $billingData->zipCode = $account->zipCode;
        $billingData->province()->associate($account->provinceId);
        $billingData->iban = 'NL74INGB5350244469';
        $billingData->save();
    }
}
