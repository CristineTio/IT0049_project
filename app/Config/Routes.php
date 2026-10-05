<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public pages
$routes->get('/', 'Pages::landing');
$routes->get('about', 'Pages::about');

// Login is only for guests; logged-in staff are sent to the home page.
$routes->group('', ['filter' => 'guest'], static function (RouteCollection $routes): void {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::attempt');
});

// Everything below requires a logged-in staff member.
$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->post('logout', 'Auth::logout');

    // The same list/create/edit/delete routes for each management page.
    $managementPages = [
        'products'  => 'Products',
        'customers' => 'Customers',
        'users'     => 'Users',
    ];

    foreach ($managementPages as $path => $controller) {
        $routes->get($path, $controller . '::index');
        $routes->get($path . '/new', $controller . '::new');
        $routes->post($path, $controller . '::create');
        $routes->get($path . '/(:num)/edit', $controller . '::edit/$1');
        $routes->post($path . '/(:num)', $controller . '::update/$1');
        $routes->post($path . '/(:num)/delete', $controller . '::delete/$1');
    }

    $routes->get('sales', 'Sales::index');
    $routes->get('sales/new', 'Sales::new');
    $routes->post('sales', 'Sales::create');
});
