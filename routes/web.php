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

$router->get('/produk', 'ProdukController@index');
$router->get('/produk/id/{id}', 'ProdukController@indexById');

$router->get('/bab', 'BabController@index');
$router->get('/bab/id/{id}', 'BabController@indexById');
$router->get('/bab/materi/{id}', 'BabController@indexByMateri');

$router->get('/soal', 'SoalController@index');
$router->get('/soal/id/{id}', 'SoalController@indexById');
$router->get('/soal/bab/{id}', 'SoalController@indexByBab');
$router->get('/soal/materi/{id}', 'SoalController@indexByMateri');
// =================================================================
//      Need Admin's Authentication
// ====================================================================

$router->post('/admin/register', 'AdminController@register');
$router->post('/admin/login', 'AdminController@login');

$router->get('/user', 'UserController@index');

$router->post('/pengajar/insert', 'PengajarController@store');
$router->post('/pengajar/update', 'PengajarController@update');
$router->post('/pengajar/delete', 'PengajarController@delete');

$router->post('/produk/insert', 'ProdukController@store');
$router->post('/produk/update', 'ProdukController@update');
$router->post('/produk/delete', 'ProdukController@delete');
$router->get('/produk/get-delete', 'ProdukController@getDelete');
$router->post('/produk/restore', 'ProdukController@restore');

$router->post('/booking/insert', 'BookingController@store');
$router->get('/booking', 'BookingController@index');
$router->get('/booking/id/{id}', 'BookingController@indexById');
$router->post('/booking/update', 'BookingController@update');
$router->post('/booking/delete', 'BookingController@delete');
$router->get('/booking/get-delete', 'BookingController@getDelete');
$router->post('/booking/restore', 'BookingController@restore');
$router->post('/booking/update-status', 'BookingController@updateStatus');

$router->post('/materi/insert', 'MateriController@store');
$router->post('/materi/update', 'MateriController@update');
$router->post('/materi/delete', 'MateriController@delete');
$router->get('/materi/get-delete', 'MateriController@getDelete');
$router->post('/materi/restore', 'MateriController@restore');

$router->post('/bab/insert', 'BabController@store');
$router->post('/bab/update', 'BabController@update');
$router->post('/bab/delete', 'BabController@delete');
$router->get('/bab/get-delete', 'BabController@getDelete');
$router->post('/bab/restore', 'BabController@restore');

$router->post('/soal/insert', 'SoalController@store');
$router->post('/soal/update', 'SoalController@update');
$router->post('/soal/delete', 'SoalController@delete');
$router->get('/soal/get-delete', 'SoalController@getDelete');
$router->post('/soal/restore', 'SoalController@restore');



