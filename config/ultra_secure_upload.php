<?php

namespace Src\Config;

return [

    'root_file_folder' => 'users_files',

     /*
    |--------------------------------------------------------------------------
    | Hosting Services Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration option allows you to define multiple hosting services
    | that your application may use. Each service is defined with its name and
    | a flag indicating whether it is the default hosting provider. This setting
    | is useful for dynamically referencing the appropriate hosting service in
    | notifications or alerts, allowing you to easily scale the application
    | when switching between different hosting services without modifying the
    | application code.
    |
    |--------------------------------------------------------------------------
    */
    'hosting_services' => [
        [
            'driver' => 'public',
            'name' => 'Localhost',
            'in_use' => true,
        ],
        [
            'driver' => 'do',
            'name' => 'Digital Ocean',
            'in_use' => true,
        ],
        [
            'driver' => 'aws',
            'name' => 'AWS',
            'in_use' => false,
        ],
        [
            'driver' => 'gcp',
            'name' => 'Google Cloud',
            'in_use' => false,
        ],
        [
            'driver' => 'ipfs',
            'name' => 'IPFS',
            'in_use' => false,
        ]
    ],

];