<?php return array (
    'realm' => 'panel',
    'tokens_temp_file' => __DIR__.'/tokens_temp.php', //Fichero donde se almacenarán los tokens temporales
    // 'oauth_consumer_key' => '88957C8D6242E37BCFD08E1FC3B63084', //Consumer key de su cuenta partner anfix
    // 'oauth_signature' => '6D18937DC78CA2F14C7EFD86BFEDB0F5',    //Clave de firma de su cuenta partner anfix
    'oauth_consumer_key' => '533F92CD8AC33AD9288A9056942739A7', //Consumer key de su cuenta partner anfix
    'oauth_signature' => 'BE46208F6455D831D189D97FFF1D2491',    //Clave de firma de su cuenta partner anfix
    'applicationIdUrl' => array(
        '1' => 'http://apps.anfix.com/os/os/parc/',
        'E' => 'http://apps.anfix.com/facturapro-servicios/gestiona/servicios/',
        '3' => 'http://apps.anfix.com/contapro/conta/',
        '5' => 'http://apps.anfix.com/documentos/',
        'e' => 'http://apps.anfix.com/digit/digit/cm/',

    ),
    'requestTokenUrl' => 'http://apps.anfix.com/os/os/parc/partner/request_token',
    'accessTokenUrl' => 'http://apps.anfix.com/os/os/parc/partner/access_token',
    'invalidateTokenUrl' => 'http://apps.anfix.com/os/os/parc/partner/invalidate_token',
    'loginUrl' => 'https://anfix.com/login-partner',
    'default_token' => [ //Si no indica ningún token de acceso en sus conexiones se utilizarán estos valores por defecto (Monocuenta)
        //'29F246DBC06AE990ED50088B850D7ED3', //Token para conexión a cuenta por defecto
        //'C740392E6480551DB8CC3BA6123A6C82'  //Secret para conexión a cuenta por defecto
        "772E1B1D0B96272DC0EA77F1C26E1CB0",
        "BCF98ADE6A11CEA1BD4FDBCEFDAF8471"
    ]
);
