<?php

//default setup is class  , action means method that will be trigger and method that will be resolve against

return [
    '/login' => ['class' => \Codediesel\RestApi\Authenticate::class 
    ,   'action' => 'create' , 'method' => 'POST' ] ,
    '/refresh/token' => ['class' => \Codediesel\RestApi\Authenticate::class 
    ,   'action' => 'update' , 'method' => 'PUT' ],

    //Hash
    '/hash/create' => [ 'class' => 
    Codediesel\RestApi\Hash::class ,   'action' => 'default' , 'method' => 'POST' ],
    '/hash/retrieve' => [ 'class' => Codediesel\RestApi\Hash::class 
    ,   'action' => 'default' , 'method' => 'GET' ],
    '/hash/delete' => [ 'class' => Codediesel\RestApi\Hash::class 
    ,   'action' => 'default' , 'method' => 'DELETE' ],
    '/hash/update' => [ 'class' => Codediesel\RestApi\Hash::class 
    ,   'action' => 'default' , 'method' => 'PUT' ],

    //Register
    '/users/create' => [ 'class' => Codediesel\RestApi\Users::class 
    ,   'action' => 'create' , 'method' => 'POST'],
    '/users/retrieve/{id}' => [ 'class' => Codediesel\RestApi\Users::class 
    ,   'action' => 'retrieve' , 'method' => 'GET'  , 'middleware' => true , 'role' => ['self', 'admin'] ],
    '/users/delete' => [ 'class' => Codediesel\RestApi\Users::class 
    ,   'action' => 'delete' , 'method' => 'DELETE', 'middleware' => true , 'role' => ['self', 'admin'] ],
    '/users/update' => [ 'class' => Codediesel\RestApi\Users::class 
    ,   'action' => 'update' , 'method' => 'PUT' , 'middleware' => true , 'role' => ['self', 'admin']],

];