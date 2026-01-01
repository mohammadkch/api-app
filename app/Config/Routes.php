<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');

$routes->get('/sshtest', 'SshTest::index');
//$routes->get('/add-openvpn', 'SshTest::addOpenVpn');
//$routes->get('/delete-openvpn', 'SshTest::deleteOpenVpn');

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
        $routes->post('update', 'Api\XuiController::update');
        $routes->post('delete', 'Api\XuiController::delete');
        $routes->get('list', 'Api\XuiController::list');
    });
});


// ---------------------------------------------
// SQLite connection test
// ---------------------------------------------
$routes->get('/dbtest', function () {
//    $db = \Config\Database::connect();
//    return $db->getPlatform(); // باید SQLite3 برگرده
    $db = \Config\Database::connect();
    try {
        $db->query("CREATE TABLE IF NOT EXISTS test_write (id INTEGER PRIMARY KEY)");
        $db->query("INSERT INTO test_write DEFAULT VALUES");
        return "Write Successful! Platform: " . $db->getPlatform();
    } catch (\Exception $e) {
        return "Read ok, but Write failed: " . $e->getMessage();
    }
});
