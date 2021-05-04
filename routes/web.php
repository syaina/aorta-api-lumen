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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');

$router->get('/pengajar', 'PengajarController@index');

// =================================================================
//      Need Admin's Authentication
// ====================================================================

$router->post('/admin/register', 'AdminController@register');
$router->post('/admin/login', 'AdminController@login');

$router->get('/user', 'UserController@index');

$router->post('/pengajar/insert', 'PengajarController@store');
$router->post('/pengajar/update', 'PengajarController@update');
$router->post('/pengajar/delete', 'PengajarController@delete');


