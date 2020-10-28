<?php

use App\Client;
use App\PointOfSaleUser;

return [

    /*
    |--------------------------------------------------------------------------
    | Indicates if the migrations are enabled
    |--------------------------------------------------------------------------
    */
    'migrations_enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Indicates if the authorization is enabled for the default methods
    |--------------------------------------------------------------------------
    */
    'authorization_enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Indicates if the default routes are enabled
    |--------------------------------------------------------------------------
    */
    'routes_enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Accounts configuration
    |--------------------------------------------------------------------------
    */
    'accounts' => [
        /*
        |--------------------------------------------------------------------------
        | Indicates if the account migrations are enabled
        |--------------------------------------------------------------------------
        */
        'enabled' => true,
        /*
        |--------------------------------------------------------------------------
        | Table where you want to store the accounts
        |--------------------------------------------------------------------------
        */
        'accounts_table' => 'Account',
        /*
        |--------------------------------------------------------------------------
        | Indicates if the billing_data migrations are enabled
        |--------------------------------------------------------------------------
        */
        'billing_data_enabled' => true,
        /*
        |--------------------------------------------------------------------------
        | Table where you want to store the billing_data
        |--------------------------------------------------------------------------
        */
        'billing_data_table' => 'BillingData',
        /*
        |--------------------------------------------------------------------------
        | Additional account parameters that will be associated to the account, example:
         'accounts_additional_attributes' => [
            'ex1' => ['name' => 'Example1', 'type' => 'string', 'nullable' => false, 'default' => true ,
                      'dataGrid' => ['default' => false, 'notSortable' => true]],
            'ex2' => ['name' => 'Example2', 'type' => 'string', 'nullable' => true, 'default' => null, 'position' => 3]
        ],
        |--------------------------------------------------------------------------
        */
        'accounts_additional_attributes' => [
            'maximumAdvance' => ['name' => 'Máximo anticipado', 'type' => 'decimal', 'nullable' => true, 'default' => null, 'total' => 12, 'places' => 6,
                'dataGrid' => [
                    'default' => false, 'type' => 'money'
                ],
            ],
            'canCreateInvitations' => ['name' => 'Puede crear invitaciones', 'type' => 'boolean', 'nullable' => false, 'default' => false,
                'dataGrid' => [
                    'possibleValues' => [false, true],
                    'configuration' => [
                        'false' => ['html' => 'No', 'translation' => 'No'],
                        'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                    ],
                ],
            ],
            'unlimitedInvitations' => ['name' => 'Invitaciones ilimitadas', 'type' => 'boolean', 'nullable' => false, 'default' => false,
                'dataGrid' => [
                    'possibleValues' => [false, true],
                    'configuration' => [
                        'false' => ['html' => 'No', 'translation' => 'No'],
                        'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                    ],
                ],
            ],
        ],
        /*
        |--------------------------------------------------------------------------
        | Additional account shadow attributes (keys)
        |--------------------------------------------------------------------------
        */
        'accounts_additional_shadow_attributes' => [],
        /*
        |--------------------------------------------------------------------------
        | Indicates if the accounts seeder is enabled
        |--------------------------------------------------------------------------
        */
        'seed_on_migrate' => true,
    ],
    /*
    |--------------------------------------------------------------------------
    | Users configuration
    |--------------------------------------------------------------------------
    */
    'users' => [
        /*
        |--------------------------------------------------------------------------
        | Indicates if the users migrations are enabled
        |--------------------------------------------------------------------------
        */
        'enabled' => true,
        /*
        |--------------------------------------------------------------------------
        | Table where you want to store the users
        |--------------------------------------------------------------------------
        */
        'users_table' => 'User',
        /*
        |--------------------------------------------------------------------------
        | Table where you want to store the user social entities table
        |--------------------------------------------------------------------------
        */
        'social_entities_table' => 'SocialEntity',
        /*
        |--------------------------------------------------------------------------
        | Additional user parameters that will be associated to the user, example:
         'users_additional_attributes' => [
            'ex1' => ['type' => 'string', 'nullable' => false, 'default' => true],
            'ex2' => ['type' => 'string', 'nullable' => true, 'default' => null]
        ],
        |--------------------------------------------------------------------------
        */
        'users_additional_attributes' => [
        ],
        /*
        |--------------------------------------------------------------------------
        | Additional users shadow attributes
        |--------------------------------------------------------------------------
        */
        'users_additional_shadow_attributes' => [],
        /*
        |--------------------------------------------------------------------------
        | Indicates if the users have the zones (province, country)
        |--------------------------------------------------------------------------
        */
        'users_with_zones' => false,
        /*
        |--------------------------------------------------------------------------
        | Indicates if the users have the extra default attributes (surname, birthDate, phone, nif)
        |--------------------------------------------------------------------------
        */
        'users_with_extra_default_attributes' => false,
        /*
        |--------------------------------------------------------------------------
        | Indicates if the users seeder is enabled
        |--------------------------------------------------------------------------
        */
        'seed_on_migrate' => true,
    ],
    /*
    |--------------------------------------------------------------------------
    | Zones configuration
    |--------------------------------------------------------------------------
    */
    'zones' => [
        /*
        |--------------------------------------------------------------------------
        | Indicates if the zones migrations are enabled
        |--------------------------------------------------------------------------
        */
        'enabled' => true,
        /*
        |--------------------------------------------------------------------------
        | Table where you want to store the zones
        |--------------------------------------------------------------------------
        */
        'table' => 'Zone',
        /*
        |--------------------------------------------------------------------------
        | Additional zones shadow attributes
        |--------------------------------------------------------------------------
        */
        'zones_additional_shadow_attributes' => [],
        /*
        |--------------------------------------------------------------------------
        | Indicates if the zones seeder is enabled
        |--------------------------------------------------------------------------
        */
        'seed_on_migrate' => true,
    ],
    /*
    |--------------------------------------------------------------------------
    | Roles configuration
    |--------------------------------------------------------------------------
    */
    'roles' => [
        /*
        |--------------------------------------------------------------------------
        | Indicates if the roles migrations are enabled
        |--------------------------------------------------------------------------
        */
        'enabled' => true,
        /*
        |--------------------------------------------------------------------------
        | Table where you want to store the roles
        |--------------------------------------------------------------------------
        */
        'roles_table' => 'Role',
        /*
        |--------------------------------------------------------------------------
        | Table where you want to store the abilities
        |--------------------------------------------------------------------------
        */
        'abilities_table' => 'Ability',
        /*
        |--------------------------------------------------------------------------
        | Table where you want to store the ability-role table
        |--------------------------------------------------------------------------
        */
        'ability_role_table' => 'AbilityRole',
        /*
        |--------------------------------------------------------------------------
        | Contains any custom conditions to be added to the index of roles.
        ['column' => 'slug', 'operator' => '<>', 'value' => 'super-admin']
        |--------------------------------------------------------------------------
        */
        'additional_roles_index_conditions' => [
            ['column' => 'slug', 'operator' => '<>', 'value' => 'cliente'],
            ['column' => 'slug', 'operator' => '<>', 'value' => 'control-de-accesos'],
            ['column' => 'slug', 'operator' => '<>', 'value' => 'punto-de-venta'],
            ['column' => 'slug', 'operator' => '<>', 'value' => 'promotor'],
            ['column' => 'slug', 'operator' => '<>', 'value' => 'promotor-descuentos'],
        ],
        /*
        |--------------------------------------------------------------------------
        | Contains any custom conditions to be added to the dataGrid of roles.
        ['column' => 'slug', 'operator' => '<>', 'value' => 'super-admin']
        |--------------------------------------------------------------------------
        */
        'additional_roles_data_grid_conditions' => [
            ['column' => 'slug', 'operator' => '<>', 'value' => 'cliente'],
            ['column' => 'slug', 'operator' => '<>', 'value' => 'control-de-accesos'],
            ['column' => 'slug', 'operator' => '<>', 'value' => 'punto-de-venta'],
            ['column' => 'slug', 'operator' => '<>', 'value' => 'promotor'],
            ['column' => 'slug', 'operator' => '<>', 'value' => 'promotor-descuentos'],
        ],
        /*
        |--------------------------------------------------------------------------
        | Contains any custom conditions to be added to the index of abilities.
        |--------------------------------------------------------------------------
        */
        'additional_abilities_index_conditions' => [
            ['column' => 'category', 'operator' => '<>', 'value' => 'cliente'],
        ],
        /*
        |--------------------------------------------------------------------------
        | Contains any custom conditions to be added to the dataGrid of abilities.
        ['column' => 'slug', 'operator' => '<>', 'value' => 'super-admin']
        |--------------------------------------------------------------------------
        */
        'additional_abilities_data_grid_conditions' => [
            ['column' => 'category', 'operator' => '<>', 'value' => 'cliente'],
        ],
        /*
        |--------------------------------------------------------------------------
        | Additional roles shadow attributes
        |--------------------------------------------------------------------------
        */
        'roles_additional_shadow_attributes' => [],
        /*
        |--------------------------------------------------------------------------
        | Indicates if the roles seeder is enabled
        |--------------------------------------------------------------------------
        */
        'seed_on_migrate' => true,
    ],
    /*
    |--------------------------------------------------------------------------
    | Plans configuration
    |--------------------------------------------------------------------------
    */
    'plans' => [
        /*
        |--------------------------------------------------------------------------
        | Indicates if the plans migrations are enabled
        |--------------------------------------------------------------------------
        */
        'enabled' => false,
        /*
        |--------------------------------------------------------------------------
        | Table where you want to store the plans
        |--------------------------------------------------------------------------
        */
        'plans_table' => 'Plan',
        /*
        |--------------------------------------------------------------------------
        | Table where you want to store the ability plan relation
        |--------------------------------------------------------------------------
        */
        'ability_plan_table' => 'AbilityPlan',
        /*
        |--------------------------------------------------------------------------
        | Table where you want to store the plan role relation
        |--------------------------------------------------------------------------
        */
        'plan_role_table' => 'PlanRole',
        /*
        |--------------------------------------------------------------------------
        | Table where you want to store the licenses
        |--------------------------------------------------------------------------
        */
        'licenses_table' => 'License',
        /*
        |--------------------------------------------------------------------------
        | Additional account parameters that will be associated to the plan, example:
         'plans_additional_attributes' => [
            'valuations_limit' => ['type' => 'unsignedInteger', 'nullable' => false, 'default' => 0]
        ],
        |--------------------------------------------------------------------------
        */
        'plans_additional_attributes' => [],
        /*
        |--------------------------------------------------------------------------
        | Additional account parameters that will be associated to the license
        |--------------------------------------------------------------------------
        */
        'licenses_additional_attributes' => [],
        /*
        |--------------------------------------------------------------------------
        | Additional plans shadow attributes
        |--------------------------------------------------------------------------
        */
        'plans_additional_shadow_attributes' => [],
        /*
        |--------------------------------------------------------------------------
        | Additional licenses shadow attributes
        |--------------------------------------------------------------------------
        */
        'licenses_additional_shadow_attributes' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | The super admin mail
    |--------------------------------------------------------------------------
    */
    'super_admin_mail' => 'superadmin@redentradas.es',

    /*
    |--------------------------------------------------------------------------
    | Min password length
    |--------------------------------------------------------------------------
    */
    'min_password_length' => 6,

    /*
    |--------------------------------------------------------------------------
    | Max password length
    |--------------------------------------------------------------------------
    */
    'max_password_length' => 30,

    /*
    |--------------------------------------------------------------------------
    | Contains any custom claims to be added to the JWT.
    | The class have to be associated to the user.
     'jwt_custom_claims' => [
        'client_id' => ['class' => \App\Client::class, 'attribute' => 'id'],
        'shop_id' => ['class' => \App\Shop::class, 'attribute' => 'id'],
    ],
    |--------------------------------------------------------------------------
    */
    'jwt_custom_claims' => [],

    /*
    |--------------------------------------------------------------------------
    | Contains the attributes to additionally check in the login.
     'login_extra_keys_to_check' => ['phone'],
    |--------------------------------------------------------------------------
    */
    'login_extra_keys_to_check' => [],

    /*
    |--------------------------------------------------------------------------
    | Contains any custom data to be added to the login response.
    | The class have to be associated to the user.
     'additional_login_data' => [
        'clientId' => ['class' => \App\Client::class, 'attribute' => 'id'],
        'shopId' => ['class' => \App\Shop::class, 'attribute' => 'id'],
        'shop' => ['class' => \App\Shop::class],
    ],
    |--------------------------------------------------------------------------
    */
    'additional_login_data' => [
        'client' => ['class' => Client::class],
        'clientId' => ['class' => Client::class, 'attribute' => 'id'],
//        'ticketOfficeUserId' => ['class' => PointOfSaleUser::class, 'attribute' => 'id'],
//        'pointOfSaleUser' => ['class' => PointOfSaleUser::class],
//        'pointOfSaleId' => ['class' => PointOfSaleUser::class, 'attribute' => 'pointOfSaleId'],
        'pointOfSale' => ['class' => PointOfSaleUser::class, 'attribute' => 'pointOfSale'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Indicates the social login redirect routes
    'social_login_redirect_routes' => [
        'client' => ['clientes/login'],
        'superadmin' => ['empresas/login'],
    ],
    |--------------------------------------------------------------------------
    */
    'social_login_redirect_routes' => [],

    /*
    |--------------------------------------------------------------------------
    | URL where you want to set the files routes
    |--------------------------------------------------------------------------
    */
    'api_base_route' => 'api/v1',

    /*
    |--------------------------------------------------------------------------
    | Password
    |--------------------------------------------------------------------------
    */
    'pass' => env('APP_PASS', 'omfgbeebitsoop'),

    /*
    |--------------------------------------------------------------------------
    | Indicates the DB connection
    |--------------------------------------------------------------------------
    */
    'db_connection' => env('DB_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Indicates the app env
    |--------------------------------------------------------------------------
    */
    'app_env' => env('APP_ENV'),

    /*
    |--------------------------------------------------------------------------
    | Indicates the app key
    |--------------------------------------------------------------------------
    */
    'app_key' => env('APP_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Indicates the app url
    |--------------------------------------------------------------------------
    */
    'app_url' => env('APP_URL'),

    /*
    |--------------------------------------------------------------------------
    | Indicates the app name
    |--------------------------------------------------------------------------
    */
    'app_name' => env('APP_NAME'),

    /*
    |--------------------------------------------------------------------------
    | Indicates the app theme
    |--------------------------------------------------------------------------
    */
    'app_theme' => env('APP_THEME'),

    /*
    |--------------------------------------------------------------------------
    | Mail variables, to customize the recovery mail
    | recovery_mail_routes example:
     'recovery_mail_routes' => [
        'client' => env('APP_URL') . '/recuperacion/',
        'superadmin' => env('APP_URL') . '/admin/recuperacion/',
    ],
    |--------------------------------------------------------------------------
    */
    'recovery_mail_title' => 'Recuperación de contraseña',

    'recovery_mail_subtitle' => null,

    'recovery_mail_content' => ['Has solicitado recuperar tu contraseña en la plataforma ' . env('APP_NAME') . '.',
        'Para confirmar que tu solicitud es correcta, y poder recuperar tu contraseña,
                        es necesario que hagas clic en el siguiente enlace:'],

    'recovery_mail_logo_url' => null,

    'recovery_mail_help_email' => null,

    'recovery_mail_help_web' => null,

    'recovery_mail_base_route' => '/recuperacion/',

    'recovery_mail_routes' => [
        'cliente' => env('CLIENT_APP_URL', 'APP_URL') . '/auth/recuperacion/',
        'control-de-accesos' => env('ACCESS_APP_URL', 'APP_URL') . '/auth/recuperacion/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Mail variables, to customize the new_password mail
    | new_password_mail_routes example:
     'new_password_mail_routes' => [
        'client' => env('APP_URL') . '/recuperacion/',
        'superadmin' => env('APP_URL') . '/admin/recuperacion/',
    ],
    |--------------------------------------------------------------------------
    */
    'new_password_mail_title' => 'Establecer contraseña',

    'new_password_mail_subtitle' => null,

    'new_password_mail_content' => ['Has solicitado establecer una nueva contraseña en la plataforma ' . env('APP_NAME') . '.',
        'Para confirmar que tu solicitud es correcta, y poder establecer tu contraseña,
                        es necesario que hagas clic en el siguiente enlace:'],

    'new_password_mail_logo_url' => null,

    'new_password_mail_help_email' => null,

    'new_password_mail_help_web' => null,

    'new_password_mail_base_route' => '/recuperacion/',

    'new_password_mail_routes' => [
        'cliente' => env('CLIENT_APP_URL', 'APP_URL') . '/auth/recuperacion/',
        'control-de-accesos' => env('ACCESS_APP_URL', 'APP_URL') . '/auth/recuperacion/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Mail variables, to customize the confirm mail
    | confirm_mail_routes example:
     'confirm_mail_routes' => [
        'client' => ['withPassword' => env('APP_URL') . '/confirmacion/', 'withoutPassword' => env('APP_URL') . '/confirmacion-credenciales/'],
        'superadmin' => ['withPassword' => env('APP_URL') . '/test/confirmacion/', 'withoutPassword' => env('APP_URL') . '/test/confirmacion-credenciales/'],
    ],
    |--------------------------------------------------------------------------
    */
    'confirm_mail_enabled' => false,

    'confirm_mail_enabled_aux' => true,

    'confirm_mail_title' => null,

    'confirm_mail_subtitle' => null,

    'confirm_mail_content' => ['Acabas de registrarte en ' . env('APP_NAME') . '.',
        'Confirma tu email para poder sacar partido de todas sus funcionalidades'],

    'confirm_mail_logo_url' => '',

    'confirm_mail_help_email' => '',

    'confirm_mail_help_web' => env('APP_URL'),

    'confirm_mail_base_route_with_password' => '/confirmacion/',

    'confirm_mail_base_route_without_password' => '/confirmacion-credenciales/',

    'confirm_mail_routes' => [
        'cliente' => [
            'withPassword' => env('CLIENT_APP_URL', 'APP_URL') . '/auth/confirmacion/',
            'withoutPassword' => env('CLIENT_APP_URL', 'APP_URL') . '/auth/confirmacion-credenciales/',
        ],
        'control-de-accesos' => [
            'withPassword' => env('ACCESS_APP_URL', 'APP_URL') . '/auth/confirmacion/',
            'withoutPassword' => env('ACCESS_APP_URL', 'APP_URL') . '/auth/confirmacion-credenciales/',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Mail config
    |--------------------------------------------------------------------------
    */
    'mail_unsubscribe_url' => null,
];
