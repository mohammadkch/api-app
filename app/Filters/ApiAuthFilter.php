<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $apiKey = $request->getHeaderLine('X-API-KEY');

        $secretKey = env('api.secret_key');

        if (empty($apiKey) || $apiKey !== $secretKey) {
            $response = Services::response();
            return $response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized Access! Invalid API Key.'
            ])->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}