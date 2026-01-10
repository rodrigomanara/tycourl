<?php
//status responses only used to ensure the api documentation display the correct error message
$responses = [
    '200' => [
        'description' => 'Successful response',
    ],
    '400' => [
        'description' => 'Bad request',
    ],
    '401' => [
        'description' => 'Unauthorized',
    ],
    '403' => [
        'description' => 'Forbidden',
    ],
    '404' => [
        'description' => 'Not found',
    ],
    '500' => [
        'description' => 'Internal server error',
    ],
];



// Default setup is class, action means method that will be triggered
//, and method that will be resolved against
return [
    '/authenticate/login' => [
        'class' => \Codediesel\RestApi\Authenticate::class,
        'action' => 'create',
        'method' => 'POST',
        'middleware' => false,
        'function' => 'create',
        'parameters' => [
         
            [
                'in' => 'query',
                'name' => 'username',
            ],
            [
                'in' => 'query',
                'name' => 'password',
                'schema' => [
                    'type' => 'password',
                ],
            ]
            
        ] ,
        'responses' => $responses
    ],
    '/authenticate/validate' => [
        'class' => \Codediesel\RestApi\Authenticate::class,
        'action' => 'retrieve',
        'method' => 'POST',
        'middleware' => false,
        'function' => 'retrieve',
        'responses' => $responses
    ],
    
    '/authenticate/refresh/token' => [
        'class' => \Codediesel\RestApi\Authenticate::class,
        'action' => 'update',
        'method' => 'PUT',
        'middleware' => false ,
        'function' => 'update',
        'responses' => $responses
    ],

    '/authenticate/request/password-change' => [
        'class' => \Codediesel\RestApi\Authenticate::class,
        'action' => 'requestPasswordChange',
        'method' => 'POST',
        'middleware' => false,
        'function' => 'requestPasswordChange',
        'responses' => $responses , 
        'parameters' => [
            [
                'in' => 'query',
                'name' => 'email',
            ],
        ]
    ],
    '/authenticate/password/change' => [
        'class' => \Codediesel\RestApi\Authenticate::class,
        'action' => 'ChangePassword',
        'method' => 'POST',
        'middleware' => false,
        'function' => 'ChangePassword',
        'responses' => $responses , 
        'parameters' => [
            [
                'in' => 'query',
                'name' => 'email',
            ],
        ]
    ],


    // Hash
    '/hash/create' => [
        'class' => Codediesel\RestApi\Hash::class,
        'action' => 'create',
        'method' => 'POST',
        'middleware' => false,
        'function' => 'create',
        'parameters' => [
            [
                'in' => 'query',
                'name' => 'url',
            ],
            [
                'in' => 'query',
                'name' => 'type',
                'schema' => [
                    'type' => 'string',
                    'enum' => ['short', 'long']
                ],
            ]
        ],
        'responses' => $responses
    ],
    '/hash/retrieve' => [
        'class' => Codediesel\RestApi\Hash::class,
        'action' => 'retrieve',
        'method' => 'POST',
        'middleware' => false,
        'function' => 'retrieve',
        'parameters' => [
            [
                'in' => 'query',
                'name' => 'hash',
            ],
            [
                'in' => 'query',
                'name' => 'type',
                'schema' => [
                    'type' => 'string',
                    'enum' => ['short', 'long']
                ],
            ],
            [
                'in' => 'query',
                'name' => 'user_id',
                'schema' => [
                    'type' => 'int'
                ],
            ]
        ],
        'responses' => $responses
    ],
    '/hash/delete' => [
        'class' => Codediesel\RestApi\Hash::class,
        'action' => 'delete',
        'method' => 'DELETE',
        'middleware' => true,
        'function' => 'delete',
        'parameters' => [
            [
                'in' => 'query',
                'name' => 'hash',
            ],
            [
                'in' => 'query',
                'name' => 'user_id',
                'schema' => [
                    'type' => 'int'
                ],
            ]
        ],
    ],
    '/hash/update' => [
        'class' => Codediesel\RestApi\Hash::class,
        'action' => 'update',
        'method' => 'PUT',
        'middleware' => true,
        'function' => 'update',
        'parameters' => [
            [
                'in' => 'query',
                'name' => 'hash',
            ],
            [
                'in' => 'query',
                'name' => 'type',
                'schema' => [
                    'type' => 'string',
                    'enum' => ['short', 'long']
                ],
            ]
        ]
    ],
    '/hash/history' => [
        'class' => Codediesel\RestApi\Hash::class,
        'action' => 'retrieve',
        'method' => 'GET',
        'middleware' => true,
        'function' => 'history',
        'parameters' => [
            'hash' => 'string',
            'type' => 'string',
            'user_id' => 'int'
        ],
        'responses' => $responses
    ],

    '/hash/history/noQrCode' => [
        'class' => Codediesel\RestApi\Hash::class,
        'action' => 'retrieve',
        'method' => 'GET',
        'middleware' => true,
        'function' => 'fetchUrlWithoutQRCode',
        'parameters' => [
            'hash' => 'string',
            'type' => 'string',
            'user_id' => 'int'
        ],
        'responses' => $responses
    ],


    '/hash/history/QrCode' => [
        'class' => Codediesel\RestApi\Hash::class,
        'action' => 'retrieve',
        'method' => 'GET',
        'middleware' => true,
        'function' => 'fetchUrlWithQRCode',
        'parameters' => [
            'hash' => 'string',
            'type' => 'string',
            'user_id' => 'int'
        ],
        'responses' => $responses
    ],
    

    // Register
    '/users/create' => [
        'class' => Codediesel\RestApi\Users::class,
        'action' => 'create',
        'method' => 'POST',
        'middleware' => false,
        'function' => 'create',
        'parameters' => [
            'username' => 'string',
            'email' => 'string',
            'password' => 'string',
            'first_name' => 'string',
            'last_name' => 'string',
            'role' => 'string'
        ],
        'responses' => $responses
    ],
    '/users/retrieve/{id}' => [
        'class' => Codediesel\RestApi\Users::class,
        'action' => 'retrieve',
        'method' => 'GET',
        'middleware' => true,
        'function' => 'retrieve',
        'parameters' => [
            'id' => 'int'
        ],
        'responses' => $responses
    ],
    '/users/delete' => [
        'class' => Codediesel\RestApi\Users::class,
        'action' => 'delete',
        'method' => 'DELETE',
        'middleware' => true,
        'role' => ['self', 'admin'],
        'function' => 'delete',
        'parameters' => [
            'id' => 'int'
        ],
        'responses' => $responses
    ],
    '/users/update' => [
        'class' => Codediesel\RestApi\Users::class,
        'action' => 'update',
        'method' => 'PUT',
        'middleware' => true,
        'role' => ['self', 'admin'],
        'function' => 'update',
        'parameters' => [
            'id' => 'int',
            'username' => 'string',
            'email' => 'string',
            'password' => 'string',
            'first_name' => 'string',
            'last_name' => 'string',
            'role' => 'string'
        ],
        'responses' => $responses
    ],
    '/users/history' => [
        'class' => Codediesel\RestApi\Users::class,
        'action' => 'history',
        'method' => 'GET',
        'middleware' => true,
        'function' => 'history',
        'parameters' => [
            
        ],
        'responses' => $responses
    ],
    // Analytics
    '/analytics/retrieve' => [
        'class' => Codediesel\RestApi\Analytics::class,
        'action' => 'retrieve',
        'method' => 'GET',
        'middleware' => true,
        'function' => 'retrieve',
        'parameters' => [
            [
                'in' => 'query',
                'name' => 'user_id',
            ],
        ],
        'responses' => $responses
    ],
    //
    '/dashboard/retrieve/total' => [
        'class' => Codediesel\RestApi\Dashboard::class,
        'action' => 'create',
        'method' => 'POST',
        'middleware' => true,
        'function' => 'getTotals',
        'parameters' => [
            [
                'in' => 'query',
                'name' => 'user_id',
            ],
            [
                'in' => 'query',
                'name' => 'start_date',
            ],
            [
                'in' => 'query',
                'name' => 'end_date',
            ],
        ],
        'responses' => $responses
    ],
    '/dashboard/retrieve/most-recent-links' => [
        'class' => Codediesel\RestApi\Dashboard::class,
        'action' => 'create',
        'method' => 'POST',
        'middleware' => true,
        'function' => 'getMostRecentsLinks',
        'parameters' => [
            [
                'in' => 'query',
                'name' => 'user_id',
            ],
        ],
        'responses' => $responses
    ],


    '/ping' => [
        'class' => Codediesel\RestApi\Dashboard::class,
        'action' => 'retrieve',
        'method' => 'get',
        'middleware' => true,
        'function' => 'retrieve',
        'parameters' => [],
        'responses' => $responses
    ],
];