<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');

$routes->get('/sshtest', 'SshTest::index');

// ---------------------------------------------
// تست اتصال SQLite
// ---------------------------------------------
$routes->get('/dbtest', function () {
    $db = \Config\Database::connect();
    return $db->getPlatform(); // باید SQLite3 برگرده
});