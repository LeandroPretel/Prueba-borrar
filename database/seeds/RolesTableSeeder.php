<?php

use Illuminate\Database\Seeder;
use Savitar\Auth\SavitarAbility;
use Savitar\Auth\SavitarRole;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->create('cliente', 'Cliente', 'Cliente de la app');
        $this->create('administrador', 'Administrador', 'Administrador de la app');
        $this->create('promotor', 'Promotor', 'Promotor de los eventos de la app');
        $this->create('promotor-descuentos', 'Promotor con descuentos', 'Promotor de los eventos de la app. Puede crear descuentos');
        $this->create('punto-de-venta', 'Punto de venta', 'Usuario de un punto de venta de la app');
        $this->create('control-de-accesos', 'Usuario de control de accesos', 'Usuario de control de accesos');
        /*
        All abilities available:
        $abilities = [
          // Place
            'places',
            'create-places',
            'edit-places',
            'delete-places',
            'restore-places',
            'delete-places-permanently',
            // Space
            'spaces',
            'create-spaces',
            'edit-spaces',
            'delete-spaces',
            'restore-spaces',
            'delete-spaces-permanently',
            // Area
            'areas',
            'create-areas',
            'edit-areas',
            'delete-areas',
            'restore-areas',
            'delete-areas-permanently',
            // Artist
            'artists',
            'create-artists',
            'edit-artists',
            'delete-artists',
            'restore-artists',
            'delete-artists-permanently',
            // Show
            'shows',
            'create-shows',
            'edit-shows',
            'delete-shows',
            'restore-shows',
            'delete-shows-permanently',
            // ShowTemplate
            'show-templates',
            'create-show-templates',
            'edit-show-templates',
            'delete-show-templates',
            'restore-show-templates',
            'delete-show-templates-permanently',
            // ShowCategory
            'show-categories',
            'create-show-categories',
            'edit-show-categories',
            'delete-show-categories',
            'restore-show-categories',
            'delete-show-categories-permanently',
            // Fare
            'fares',
            'create-fares',
            'edit-fares',
            'delete-fares',
            'restore-fares',
            'delete-fares-permanently',
            // Session
            'sessions',
            'create-sessions',
            'edit-sessions',
            'delete-sessions',
            'restore-sessions',
            'delete-sessions-permanently',
            // Ticket
            'tickets',
            'create-tickets',
            'edit-tickets',
            'delete-tickets',
            'restore-tickets',
            'delete-tickets-permanently',
            // Order
            'orders',
            'create-orders',
            'edit-orders',
            'delete-orders',
            'restore-orders',
            'delete-orders-permanently',
            // Client
            'clients',
            'create-clients',
            'edit-clients',
            'delete-clients',
            'restore-clients',
            'delete-clients-permanently',
            // Consultation
            'consultations',
            'create-consultations',
            'edit-consultations',
            'delete-consultations',
            'restore-consultations',
            'delete-consultations-permanently',
            // Ticket offices
            'points-of-sale',
            'create-points-of-sale',
            'edit-points-of-sale',
            'delete-points-of-sale',
            'restore-points-of-sale',
            'delete-points-of-sale-permanently',
            // Others
            'stats',
            'configuration',
            'zones',
            'profile',
        ];
        */

        $abilities = [
            // Place
            'places',
            'create-places',
            'edit-places',
            'delete-places',
            'restore-places',
            'delete-places-permanently',
            // Space
            'spaces',
            'create-spaces',
            'edit-spaces',
            'delete-spaces',
            'restore-spaces',
            'delete-spaces-permanently',
            // Area
            'areas',
            'create-areas',
            'edit-areas',
            'delete-areas',
            'restore-areas',
            'delete-areas-permanently',
            // Artist
            'artists',
            'create-artists',
            'edit-artists',
            'delete-artists',
            'restore-artists',
            'delete-artists-permanently',
            // Show
            'shows',
            'create-shows',
            'edit-shows',
            'delete-shows',
            'restore-shows',
            'delete-shows-permanently',
            // ShowTemplate
            'show-templates',
            'create-show-templates',
            'edit-show-templates',
            'delete-show-templates',
            'restore-show-templates',
            'delete-show-templates-permanently',
            // ShowCategory
            'show-categories',
            'create-show-categories',
            'edit-show-categories',
            'delete-show-categories',
            'restore-show-categories',
            'delete-show-categories-permanently',
            // Fare
            'fares',
            'create-fares',
            'edit-fares',
            'delete-fares',
            'restore-fares',
            'delete-fares-permanently',
            // Session
            'sessions',
            'create-sessions',
            'edit-sessions',
            'delete-sessions',
            'restore-sessions',
            'delete-sessions-permanently',
//            // Ticket
//            'tickets',
//            'create-tickets',
//            'edit-tickets',
//            'delete-tickets',
//            'restore-tickets',
//            'delete-tickets-permanently',
//            // Order
            'orders',
            'create-orders',
            'edit-orders',
            'delete-orders',
            'restore-orders',
            'delete-orders-permanently',
            // Order returns
            'order-returns-with-distribution',
            'order-returns',
            'create-order-returns',
            'edit-order-returns',
            'delete-order-returns',
            'restore-order-returns',
            'delete-order-returns-permanently',
            // Client
            'clients',
            'create-clients',
            'edit-clients',
            'delete-clients',
            'restore-clients',
            'delete-clients-permanently',
            // Consultation
            'consultations',
//            'create-consultations',
//            'edit-consultations',
            'delete-consultations',
            'restore-consultations',
            'delete-consultations-permanently',
            // Points of sale
            'points-of-sale',
            'create-points-of-sale',
            'edit-points-of-sale',
            'delete-points-of-sale',
            'restore-points-of-sale',
            'delete-points-of-sale-permanently',
            // Ticket office users
            'point-of-sale-users',
            'create-point-of-sale-users',
            'edit-point-of-sale-users',
            'delete-point-of-sale-users',
            'restore-point-of-sale-users',
            'delete-point-of-sale-users-permanently',
            // Others
            'stats',
            'configuration',
            'zones',
            'profile',
            // Accesses
            'accesses',
            'create-accesses',
            'edit-accesses',
            'delete-accesses',
            // Ticket seasons & vouchers (Abonos y bonos)
            'ticket-seasons',
            'create-ticket-seasons',
            'edit-ticket-seasons',
            'delete-ticket-seasons',
            'restore-ticket-seasons',

            'ticket-vouchers',
            'create-ticket-vouchers',
            'edit-ticket-vouchers',
            'delete-ticket-vouchers',
            'restore-ticket-vouchers',
            // Enterprises
            'enterprises',
            'create-enterprises',
            'edit-enterprises',
            'delete-enterprises',
            'restore-enterprises',
        ];

        $this->associateAbilities('administrador', $abilities);

        $abilities = [
            'orders',
            'create-orders',
            'profile',
        ];

        $this->associateAbilities('cliente', $abilities);

        $abilities = [
            // Place
            'places',
            // Space
            'spaces',
            // Area
            'areas',
            // ShowTemplate
            'show-templates',
            'create-show-templates',
            'edit-show-templates',
            // 'delete-show-templates',
            // 'restore-show-templates',
            // 'delete-show-templates-permanently',
            // ShowCategory
            'show-categories',
            // Artist
            'artists',
            'create-artists',
            'edit-artists',
            // 'delete-artists',
            // 'restore-artists',
            // 'delete-artists-permanently',
            // Order
            'orders',
            // Order returns
            'order-returns',
            // Client
            // 'clients',
            // Points of sale
            'points-of-sale',
            // Fare
            'fares',
            'create-fares',
            'edit-fares',
            'delete-fares',
            'restore-fares',
            'delete-fares-permanently',
            // Show
            'shows',
            'create-shows',
            'edit-shows',
            'delete-shows',
            'restore-shows',
            'delete-shows-permanently',
            // Session
            'sessions',
            'create-sessions',
            'edit-sessions',
            'delete-sessions',
            'restore-sessions',
            'delete-sessions-permanently',
            // Others
            'stats',
            'profile',
            // Accesses
            'accesses',
            // Ticket seasons & vouchers (Abonos y bonos)
            'ticket-seasons',
            'create-ticket-seasons',
            'edit-ticket-seasons',
            'delete-ticket-seasons',

            'ticket-vouchers',
            'create-ticket-vouchers',
            'edit-ticket-vouchers',
            'delete-ticket-vouchers',
            // Enterprises
            'enterprises',
        ];
        $this->associateAbilities('promotor', $abilities);

        $abilities[] = 'configuration';
        $abilities[] = 'discounts';
        $abilities[] = 'create-discounts';
        $abilities[] = 'edit-discounts';
        // $abilities[] = 'delete-discounts';

        $this->associateAbilities('promotor-descuentos', $abilities);

        $abilities = [
            'profile',
            // Order
            'orders',
            'create-orders',
            // Order returns
            'order-returns',
            'create-order-returns',
            // Ticket sales
            'ticket-sales',
            'create-ticket-sales',
            'edit-ticket-sales',
            'delete-ticket-sales',
            // Ticket collections
            'ticket-collections',
            'create-ticket-collections',
            // Ticket office mode
            'ticket-office',
            // Session
            'sessions',
            // Discounts
            'discounts',
            // 'create-discounts',
            // 'edit-discounts',
            // 'delete-discounts',
            // 'restore-discounts',
            // 'delete-discounts-permanently',
            // Enterprises
            'enterprises',
            // Client
            'clients',
        ];
        $this->associateAbilities('punto-de-venta', $abilities);

        $abilities = [
            'profile',
            // Session
            'sessions',
            // Accesses
            'accesses',
            'create-accesses',
        ];
        $this->associateAbilities('control-de-accesos', $abilities);
    }

    /**
     * @param $slug
     * @param $name
     * @param $description
     */
    private function create($slug, $name, $description): void
    {
        /** @var SavitarRole $role */
        $role = SavitarRole::where('slug', $slug)->firstOrNew([]);
        $role->name = $name;
        $role->slug = $slug;
        $role->description = $description;
        $role->save();
    }

    /**
     * @param $roleSlug
     * @param $abilities
     */
    private function associateAbilities($roleSlug, $abilities): void
    {
        /** @var SavitarRole $role */
        $role = SavitarRole::whereSlug($roleSlug)->first();
        $abilitiesIds = [];
        foreach ($abilities as $ability) {
            $abilityModel = SavitarAbility::whereSlug($ability)->first();
            if ($abilityModel) {
                $abilitiesIds[] = $abilityModel->id;
            }
        }
        $role->abilities()->sync($abilitiesIds);
    }
}
