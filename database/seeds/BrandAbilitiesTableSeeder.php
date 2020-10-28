<?php

use Illuminate\Database\Seeder;
use Savitar\Auth\SavitarAbility;
use Savitar\Auth\SavitarRole;

class BrandAbilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Discounts
        $this->create('brands', 'Marcas blancas', 'Marcas', 'Permiso para ver las marcas blancas');
        $this->create('create-brands', 'Crear marcas blancas', 'Marcas', 'Permiso para crear las marcas blancas');
        $this->create('edit-brands', 'Editar marcas blancas', 'Marcas', 'Permiso para editar las marcas blancas');
        $this->create('delete-brands', 'Eliminar marcas blancas', 'Marcas', 'Permiso para eliminar marcas blancas');
        $this->create('restore-brands', 'Restaurar marcas blancas', 'Marcas', 'Permiso para restaurar marcas blancas');
        $this->create('delete-brands-permanently', 'Eliminar marcas blancas permanentemente', 'Descuentos', 'Permiso para eliminar marcas blancas permanentemente');

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
            // Brands
            'brands',
            'create-brands',
            'edit-brands',
            'delete-brands',
            'restore-brands',
        ];

        $this->associateAbilities('administrador', $abilities);
    }

    /**
     * @param string $slug
     * @param string $name
     * @param string|null $category
     * @param string|null $description
     */
    private function create(string $slug, string $name, ?string $category = null, ?string $description = null): void
    {
        /** @var SavitarAbility $ability */
        $ability = SavitarAbility::where('slug', $slug)->firstOrNew([]);
        $ability->slug = $slug;
        $ability->name = $name;
        $ability->category = $category;
        $ability->description = $description;
        $ability->save();
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
