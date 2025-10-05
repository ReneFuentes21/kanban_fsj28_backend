<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Panel de Orígenes Permitidos (Allowed Origins)
    |--------------------------------------------------------------------------
    |
    | Aquí puedes especificar los dominios desde los cuales se permitirán
    | las solicitudes CORS. Para desarrollo y pruebas con Swagger,
    | se establece en '*' para permitir cualquier origen.
    |
    | ¡ADVERTENCIA! No se recomienda usar '*' en producción.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], 

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];