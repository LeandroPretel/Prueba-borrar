<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table where you want to store the users
    |--------------------------------------------------------------------------
    */
    'users_table' => 'User',
    /*
    |--------------------------------------------------------------------------
    | Table where you want to store the notifications
    |--------------------------------------------------------------------------
    */
    'notifications_table' => 'Notification',

    /*
    |--------------------------------------------------------------------------
    | URL where you want to set the notification routes
    |--------------------------------------------------------------------------
    */
    'api_base_route' => 'api/v1/notifications',

    /*
    |--------------------------------------------------------------------------
    | Middlewares that should be applied to the URL
    |--------------------------------------------------------------------------
    |
    | The value should be an array of fully qualified
    | class names of the middleware classes.
    |
    | Eg: [Authenticate::class, CheckForMaintenanceMode::class]
    | Don't forget to import the classes at the top!
    |
    */
    'middlewares' => [],

    /*
    |--------------------------------------------------------------------------
    | Indicates the DB connection
    |--------------------------------------------------------------------------
    */
    'db_connection' => env('DB_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Indicates if the notifications use logging
    |--------------------------------------------------------------------------
    */
    'logging_enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Indicates if the push notifications are enabled
    |--------------------------------------------------------------------------
    */
    'push_notifications_enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Table where you want to store the push subscriptions
    |--------------------------------------------------------------------------
    */
    'push_subscriptions_table' => env('WEBPUSH_DB_TABLE'),

    /*
    |--------------------------------------------------------------------------
    | Indicates the app url
    |--------------------------------------------------------------------------
    */
    'app_url' => env('APP_URL'),

    /*
    |--------------------------------------------------------------------------
    | Indicates the queue of the notifications
    |--------------------------------------------------------------------------
    */
    'notifications_queue' => 'notifications',

    /*
    |--------------------------------------------------------------------------
    | Indicates the delay to send the queued notifications
    |--------------------------------------------------------------------------
    */
    'queued_notifications_delay' => null,
];
