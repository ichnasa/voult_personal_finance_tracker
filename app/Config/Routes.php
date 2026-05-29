<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth routes (no filter)
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/processLogin', 'Auth::processLogin');
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/processRegister', 'Auth::processRegister');
$routes->get('auth/logout', 'Auth::logout');

// Google OAuth
$routes->get('auth/google', 'Auth::googleLogin');
$routes->get('auth/google/callback', 'Auth::googleCallback');

// Protected routes
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('dashboard', 'Home::index');

    // Pemasukan
    $routes->get('pemasukan', 'Pemasukan::index');
    $routes->get('pemasukan/create', 'Pemasukan::create');
    $routes->post('pemasukan/store', 'Pemasukan::store');
    $routes->get('pemasukan/edit/(:num)', 'Pemasukan::edit/$1');
    $routes->post('pemasukan/update/(:num)', 'Pemasukan::update/$1');
    $routes->get('pemasukan/delete/(:num)', 'Pemasukan::delete/$1');

    // Pengeluaran
    $routes->get('pengeluaran', 'Pengeluaran::index');
    $routes->get('pengeluaran/create', 'Pengeluaran::create');
    $routes->post('pengeluaran/store', 'Pengeluaran::store');
    $routes->get('pengeluaran/edit/(:num)', 'Pengeluaran::edit/$1');
    $routes->post('pengeluaran/update/(:num)', 'Pengeluaran::update/$1');
    $routes->get('pengeluaran/delete/(:num)', 'Pengeluaran::delete/$1');

    // Budgeting
    $routes->get('budgeting', 'Budgeting::index');
    $routes->get('budgeting/create', 'Budgeting::create');
    $routes->post('budgeting/store', 'Budgeting::store');
    $routes->get('budgeting/edit/(:num)', 'Budgeting::edit/$1');
    $routes->post('budgeting/update/(:num)', 'Budgeting::update/$1');
    $routes->get('budgeting/delete/(:num)', 'Budgeting::delete/$1');

    // Wishlist
    $routes->get('wishlist', 'Wishlist::index');
    $routes->get('wishlist/create', 'Wishlist::create');
    $routes->post('wishlist/store', 'Wishlist::store');
    $routes->get('wishlist/edit/(:num)', 'Wishlist::edit/$1');
    $routes->post('wishlist/update/(:num)', 'Wishlist::update/$1');
    $routes->get('wishlist/delete/(:num)', 'Wishlist::delete/$1');

    // Tabungan
    $routes->get('tabungan', 'Tabungan::index');
    $routes->get('tabungan/create', 'Tabungan::create');
    $routes->post('tabungan/store', 'Tabungan::store');
    $routes->get('tabungan/edit/(:num)', 'Tabungan::edit/$1');
    $routes->post('tabungan/update/(:num)', 'Tabungan::update/$1');
    $routes->get('tabungan/delete/(:num)', 'Tabungan::delete/$1');

    // Laporan
    $routes->get('laporan', 'Laporan::index');
    $routes->get('laporan/export', 'Laporan::export');

    // Profile
    $routes->get('profile', 'Profile::index');
    $routes->post('profile/update', 'Profile::update');
    $routes->post('profile/updatePassword', 'Profile::updatePassword');
    $routes->post('profile/updateAvatar', 'Profile::updateAvatar');

    // Pengaturan
    $routes->get('pengaturan', 'Pengaturan::index');
    $routes->post('pengaturan/kategori/store', 'Pengaturan::storeKategori');
    $routes->post('pengaturan/kategori/update/(:num)', 'Pengaturan::updateKategori/$1');
    $routes->get('pengaturan/kategori/delete/(:num)', 'Pengaturan::deleteKategori/$1');
    $routes->post('pengaturan/defaults/update', 'Pengaturan::updateDefaults');
});
