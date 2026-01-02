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
// API routes
// --------------------
$routes->group('api', ['filter' => 'api_auth'], function ($routes) {

    // OpenVPN Management
    $routes->group('openvpn', function ($routes) {
        $routes->post('add', 'Api\OpenVpnController::add');
        $routes->post('delete', 'Api\OpenVpnController::delete');
        $routes->get('list', 'Api\OpenVpnController::list'); // This now handles Sync & Download
        $routes->get('download/(:any)', 'Api\OpenVpnController::getFile/$1');
    });

    // XUI Management
    $routes->group('xui', function ($routes) {
        $routes->post('add', 'Api\XuiController::add');
        $routes->post('update', 'Api\XuiController::update');
        $routes->post('delete', 'Api\XuiController::delete');
        $routes->get('list', 'Api\XuiController::list');
        $routes->get('download/(:any)', 'Api\XuiController::download/$1'); // اضافه شد
    });

});