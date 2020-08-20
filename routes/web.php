<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->post('register',   'UserController@store');
$router->post('auth',       'AuthController@store');

$router->delete('auth',     ['middleware' => 'auth:api', 'uses' => 'AuthController@destroy']);

$router->get('banks',       ['middleware' => 'auth:api', 'uses' => 'BankController@index']);

$router->get('transfers',   ['middleware' => 'auth:api', 'uses' => 'TransferController@index']);
$router->post('transfers',  ['middleware' => 'auth:api', 'uses' => 'TransferController@store']);

$router->get('recipients',  ['middleware' => 'auth:api', 'uses' => 'RecipientController@index']);
$router->post('recipients', ['middleware' => 'auth:api', 'uses' => 'RecipientController@store']);

$router->delete('recipients/{recipient_code}',  ['middleware' => 'auth:api', 'uses' => 'RecipientController@destroy']);


