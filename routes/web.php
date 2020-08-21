<?php

$router->get('/',           function() {return 'Welcome to Krass!';});

$router->post('register',   'UserController@store');
$router->post('auth',       'AuthController@store');

$router->delete('auth',     ['middleware' => 'auth:api', 'uses' => 'AuthController@destroy']);

$router->get('banks',       ['middleware' => 'auth:api', 'uses' => 'BankController@index']);

$router->get('transfers',   ['middleware' => 'auth:api', 'uses' => 'TransferController@index']);
$router->post('transfers',  ['middleware' => 'auth:api', 'uses' => 'TransferController@store']);

$router->get('recipients',  ['middleware' => 'auth:api', 'uses' => 'RecipientController@index']);
$router->post('recipients', ['middleware' => 'auth:api', 'uses' => 'RecipientController@store']);

$router->delete('recipients/{recipient_code}',  ['middleware' => 'auth:api', 'uses' => 'RecipientController@destroy']);


