<?php

namespace App\Observers;

use App\User;
use Exception;
use Savitar\Auth\SavitarAccount;
use Savitar\Auth\SavitarRole;
use Savitar\Auth\SavitarUser;

class SavitarAccountObserver
{
    /**
     * Handle the savitarAccount "saving" event.
     *
     * @param SavitarAccount $savitarAccount
     * @return void
     * @noinspection NotOptimalIfConditionsInspection
     */
    public function saving(SavitarAccount $savitarAccount): void
    {
        // Fill account data with the contact info
        if (request()->filled('contactPhone')) {
            $savitarAccount->phone = request()->input('contactPhone');
        }

        // Fill account data with the billingData
        if (request()->filled('billingData.0.nif')) {
            $savitarAccount->nif = request()->input('billingData.0.nif');
        }
        if (request()->filled('billingData.0.city')) {
            $savitarAccount->city = request()->input('billingData.0.city');
        }
        if (request()->filled('billingData.0.address')) {
            $savitarAccount->address = request()->input('billingData.0.address');
        }
        if (request()->filled('billingData.0.zipCode')) {
            $savitarAccount->zipCode = request()->input('billingData.0.zipCode');
        }
        if (request()->filled('billingData.0.provinceId')) {
            $savitarAccount->province()->associate(request()->input('billingData.0.provinceId'));
        }

        // Fill user data
        if (request()->has('name') || request()->has('userPassword') ||
            request()->has('userCanReceiveNotifications') || request()->has('userCanReceiveEmails') ||
            request()->has('userCantReceiveNotifications') || request()->has('userCantReceiveEmails')) {
            /** @var User $user */
            $user = $savitarAccount->users()->first();
            if ($user) {
                if (request()->has('name')) {
                    $user->name = request()->json('name');
                }
                if (request()->has('canManageDiscounts') && request()->json('canManageDiscounts')) {
                    $user->role()->associate(SavitarRole::whereSlug('promotor-descuentos')->first());
                } else {
                    $user->role()->associate(SavitarRole::whereSlug('promotor')->first());
                }
                if (request()->has('userPassword') && request()->filled('userPassword')) {
                    $user->password = bcrypt(request()->json('userPassword'));
                }
                if (request()->has('userCanReceiveNotifications')) {
                    $user->canReceiveNotifications = request()->json('userCanReceiveNotifications');
                }
                if (request()->has('userCanReceiveEmails')) {
                    $user->canReceiveEmails = request()->json('userCanReceiveEmails');
                }
                if (request()->has('userCantReceiveNotifications')) {
                    $user->canReceiveNotifications = !request()->json('userCantReceiveNotifications');
                }
                if (request()->has('userCantReceiveEmails')) {
                    $user->canReceiveEmails = !request()->json('userCantReceiveEmails');
                }
                $user->save();
            }
        }
    }

    /**
     * Handle the savitarAccount "created" event.
     *
     * @param SavitarAccount $savitarAccount
     * @return void
     */
    public function created(SavitarAccount $savitarAccount): void
    {
        if (request()->json('userEmail')) {
            $user = new SavitarUser();
            $user->account()->associate($savitarAccount);
            if (request()->json('canManageDiscounts')) {
                $user->role()->associate(SavitarRole::whereSlug('promotor-descuentos')->first());
            } else {
                $user->role()->associate(SavitarRole::whereSlug('promotor')->first());
            }
            $user->email = request()->json('userEmail');
            $user->name = request()->json('name');
            $user->save();
        }
    }

    /**
     * Handle the account "deleted" event.
     *
     * @param SavitarAccount $savitarAccount
     * @return void
     * @throws Exception
     */
    public function deleted(SavitarAccount $savitarAccount): void
    {
        $users = $savitarAccount->users;
        foreach ($users as $user) {
            $user->deletedBy = $savitarAccount->deletedBy;
            $user->save();
            $user->delete();
        }
    }

}
