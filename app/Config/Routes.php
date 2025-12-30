<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');

$routes->get('/sshtest', 'SshTest::index');
$routes->get('/add-openvpn', 'SshTest::addOpenVpn');
$routes->get('/delete-openvpn', 'SshTest::deleteOpenVpn');

// --------------------
// API routes
// --------------------
$routes->group('api', function ($routes) {
    $routes->group('openvpn', function ($routes) {
        $routes->post('add', 'Api\OpenVpnController::add');
        $routes->post('delete', 'Api\OpenVpnController::delete');
    });

    $routes->group('xui', function ($routes) {
        $routes->post('add', 'Api\XuiController::add');
    });
});


// ---------------------------------------------
// SQLite connection test
// ---------------------------------------------
$routes->get('/dbtest', function () {
    $db = \Config\Database::connect();
    return $db->getPlatform(); // باید SQLite3 برگرده
});
