<?php

return [

    'session' => 'module_authenticate',

    'cookie' => 'module_authenticate',

    'persistences' => [

        'single' => false,

    ],

    'activations' => [

        'expires' => 259200, // 3 days

        'lottery' => [2, 100],

    ],

    'reminders' => [

        'expires' => 14400,

        'lottery' => [2, 100],

    ]
];
