<?php

namespace App\Filters;

use \CodeIgniter\Filters\FilterInterface;
use \CodeIgniter\HTTP\RequestInterface;
use \CodeIgniter\HTTP\ResponseInterface;


class AdminAuthFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        $authentication = service('Authentication');
        $router = service('router');

        $controllerName = $router->controllerName();
        $className = str_replace('_', '-', strtolower(basename(str_replace('\\', '/', $controllerName))));
        $methodName = $router->methodName();

        $authentication->setControllerName($controllerName);
        $authentication->setClassName($className);
        $authentication->setMethodName($methodName);

/*        echo $controllerName;
        echo '<hr><br>';
        echo $className;
        echo '<hr><br>';
        echo $methodName;
        echo '<hr><br>';
        var_dump($authentication->isLoggedIn());
        exit();*/


        if ($className == 'logincontroller') {
            if ($authentication->isLoggedIn() === true) {
                return redirect()->to('admin/dashboard');
            }
        } else {
            if ($authentication->isLoggedIn() === false) {
                return redirect()->to('admin/login');
            }
        }

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}