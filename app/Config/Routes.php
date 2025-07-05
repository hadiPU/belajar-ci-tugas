<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Halaman Utama
$routes->get('/', 'Home::index', ['filter' => 'auth']);

// Autentikasi
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login', ['filter' => 'redirect']);
$routes->get('logout', 'AuthController::logout');

// Produk (dengan filter login)
$routes->group('produk', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'ProdukController::index');
    $routes->post('', 'ProdukController::create');
    $routes->post('edit/(:any)', 'ProdukController::edit/$1');
    $routes->get('delete/(:any)', 'ProdukController::delete/$1');
    $routes->get('download', 'ProdukController::download');
});

// Keranjang (Cart)
$routes->group('keranjang', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'TransaksiController::index');
    $routes->post('', 'TransaksiController::cart_add');
    $routes->post('edit', 'TransaksiController::cart_edit');
    $routes->get('delete/(:any)', 'TransaksiController::cart_delete/$1');
    $routes->get('clear', 'TransaksiController::cart_clear');
    $routes->post('tambah', 'TransaksiController::add');
});

// Checkout & Pengiriman
$routes->get('checkout', 'TransaksiController::checkout', ['filter' => 'auth']);
$routes->post('buy', 'TransaksiController::buy', ['filter' => 'auth']);
$routes->get('get-location', 'TransaksiController::getLocation', ['filter' => 'auth']);
$routes->get('get-cost', 'TransaksiController::getCost', ['filter' => 'auth']);

// Kategori Produk
$routes->get('product-category', 'Product_CategoryController::index');
$routes->post('product-category/store', 'Product_CategoryController::store');
$routes->post('product-category/update/(:num)', 'Product_CategoryController::update/$1');
$routes->get('product-category/delete/(:num)', 'Product_CategoryController::delete/$1');

// Halaman Lain
$routes->get('profile', 'Home::profile', ['filter' => 'auth']);
$routes->get('faq', 'Home::faq', ['filter' => 'auth']);
$routes->get('contact', 'Home::contact', ['filter' => 'auth']);

// API Resource
$routes->resource('api', ['controller' => 'apiController']);

$routes->get('diskon', 'DiskonController::index');
$routes->post('diskon/store', 'DiskonController::store');
$routes->post('diskon/update/(:num)', 'DiskonController::update/$1');
$routes->get('diskon/delete/(:num)', 'DiskonController::delete/$1');



$routes->get('admin/transaksi', 'AdminController::transaksi');
$routes->post('admin/transaksi/update_status/(:num)', 'AdminController::update_status/$1');