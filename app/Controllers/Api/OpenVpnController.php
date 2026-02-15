<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\VpnServer\OpenVpnService;

class OpenVpnController extends BaseController
{
    /**
     * Add a VPN client
     */
    public function add()
    {
        $serverId = $this->request->getPost('server_id');
        $client = $this->request->getPost('client');

        // Validation
        if (!$serverId || !$client) {
            return $this->response->setJSON([
                'status' => 'error',
                'error' => 'SERVER_ID_AND_CLIENT_REQUIRED'
            ])->setStatusCode(400);
        }

        try {
            $vpn = new OpenVpnService((int)$serverId);
            $result = $vpn->addClient($client);
            return $this->response->setJSON($result);
        } catch (\RuntimeException $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'error' => $e->getMessage()
            ])->setStatusCode(400);
        }
    }

    /**
     * Delete (revoke) a VPN client
     */
    public function delete()
    {
        $serverId = $this->request->getPost('server_id');
        $client = $this->request->getPost('client');

        if (!$serverId || !$client) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'SERVER_ID_AND_CLIENT_REQUIRED'
            ])->setStatusCode(400);
        }

        try {
            $vpn = new OpenVpnService((int)$serverId);
            $result = $vpn->deleteClient($client);
            return $this->response->setJSON($result);
        } catch (\RuntimeException $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'error' => $e->getMessage()
            ])->setStatusCode(400);
        }
    }

    /**
     * List all VPN clients for a server
     */
    public function list()
    {
        $serverId = $this->request->getGet('server_id');

        if (!$serverId) {
            return $this->response->setJSON([
                'status' => 'error',
                'error' => 'SERVER_ID_REQUIRED'
            ])->setStatusCode(400);
        }

        try {
            $vpn = new OpenVpnService((int)$serverId);
            return $this->response->setJSON($vpn->listClients());
        } catch (\RuntimeException $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'error' => $e->getMessage()
            ])->setStatusCode(400);
        }
    }

    /**
     * Download an existing client's .ovpn file
     */
    public function download(int $serverId, string $clientName)
    {
        try {
            $vpn = new OpenVpnService($serverId);
            $path = $vpn->getFilePath($clientName);

            if (!$path) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'error'  => 'FILE_NOT_FOUND'
                ])->setStatusCode(404);
            }

            return $this->response->download($path, null)->setFileName("{$clientName}.ovpn");
        } catch (\RuntimeException $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'error' => $e->getMessage()
            ])->setStatusCode(400);
        }
    }
}