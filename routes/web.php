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

$router->get('/materi', 'MateriController@index');
$router->get('/materi/id/{id}', 'MateriController@indexById');

// =================================================================
//      Need Admin's Authentication
// ====================================================================

$router->post('/admin/register', 'AdminController@register');
$router->post('/admin/login', 'AdminController@login');

$router->get('/user', 'UserController@index');

$router->post('/pengajar/insert', 'PengajarController@store');
$router->post('/pengajar/update', 'PengajarController@update');
$router->post('/pengajar/delete', 'PengajarController@delete');

$router->post('/materi/insert', 'MateriController@store');
$router->post('/materi/update', 'MateriController@update');
$router->post('/materi/delete', 'MateriController@delete');
$router->get('/materi/get-delete', 'MateriController@getDelete');
$router->post('/materi/restore', 'MateriController@restore');





