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
$routes->get('/admin/login', 'Admin\LoginController::index');
$routes->post('/admin/login', 'Admin\LoginController::authenticate');
$routes->get('/admin/dashboard', 'Admin\DashboardController::index');

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