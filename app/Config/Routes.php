<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');

$routes->get('/sshtest', 'SshTest::index');
$routes->get('/add-openvpn', 'SshTest::addOpenVpn');
$routes->get('/delete-openvpn', 'SshTest::deleteOpenVpn');

// ---------------------------------------------
// تست اتصال SQLite
// ---------------------------------------------
$routes->get('/dbtest', function () {
    $db = \Config\Database::connect();
    return $db->getPlatform(); // باید SQLite3 برگرده
});