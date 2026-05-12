<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --------------------
// Testing routes
// --------------------
$routes->get('/sshtest', 'SshTest::index');
$routes->get('/dbtest', function () {
    $db = \Config\Database::connect();
    return "Platform: " . $db->getPlatform();
});

// --------------------
// admin panel routes
// --------------------


$routes->group('admin', ['filter' => 'admin_auth'], function ($routes) {
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('dashboard', 'Admin\DashboardController::index');

    $routes->get('login', 'Admin\LoginController::index');
    $routes->post('login', 'Admin\LoginController::authenticate');

    $routes->get('logout', 'Admin\LogoutController::index');
    $routes->post('logout', 'Admin\LogoutController::index');

    // users
    $routes->get('user', 'Admin\UserController::index');
    $routes->post('user', 'Admin\UserController::index');
    $routes->get('user/create', 'Admin\UserController::create');
    $routes->post('user/create/handle', 'Admin\UserController::formHandler/create');
    $routes->get('user/edit/(:num)', 'Admin\UserController::edit/$1');
    $routes->post('user/edit/handle/(:num)', 'Admin\UserController::formHandler/edit/$1');
    $routes->delete('user/delete/(:num)', 'Admin\UserController::delete/$1');
    $routes->post('admin/user/updateCityOptions', 'Admin\UserController::updateCityOptions');
});


// --------------------
// API routes
// --------------------
$routes->group('api', ['filter' => 'api_auth'], function ($routes) {

    // OpenVPN Management
    $routes->group('openvpn', function ($routes) {
        $routes->post('add', 'Api\OpenVpnController::add');
        $routes->post('delete', 'Api\OpenVpnController::delete');
        $routes->get('list', 'Api\OpenVpnController::list');  // ?server_id=1
        $routes->get('download/(:num)/(:any)', 'Api\OpenVpnController::download/$1/$2');
    });

    // XUI Management
    $routes->group('xui', function ($routes) {
        $routes->post('add', 'Api\XuiController::add');
        $routes->post('update', 'Api\XuiController::update');
        $routes->post('delete', 'Api\XuiController::delete');
        $routes->get('list', 'Api\XuiController::list');  // ?server_id=1
        $routes->get('download/(:num)/(:any)', 'Api\XuiController::download/$1/$2');
    });

});