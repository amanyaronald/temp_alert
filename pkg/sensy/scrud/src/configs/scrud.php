<?php

return [



    'directories' => [

        /**
         * * The default directory for models
         * * @var string
         */
        'model' => 'app/Models/',

        /**
         * * The default directory for services
         * * @var string
         */
        'service' => ['api'=>'app/Http/Services/Api/','web'=>'app/Http/Services/'],

        /**
         * * The default directory for controllers
         * * @var string
         */
        'controller' => ['web'=>'app/Http/Controllers/','api'=>'app/Http/Controllers/Api/'],
    ],

    'class' => [

        /**
         * * The default class prefix for models
         * * @var string
         */
        'model' => '\App\Models\\',

        /**
         * * The default class prefix for services
         * * @var string
         */
        'service' => ['api'=>'\App\Http\Services\Api\\','web'=>'App\Http\Services\\'],

        /**
         * * The default class prefix for controllers
         * * @var string
         */
        'controller' => ['api'=>'\App\Http\Controllers\Api\\','web'=>'\App\Http\Controllers\\'],
    ],
];
