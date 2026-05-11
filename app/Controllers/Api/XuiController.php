<?php

namespace App\Controllers\Api;

use App\Controllers\ApiBaseController;
use App\Services\VpnServer\XuiService;

class XuiController extends ApiBaseController
{
    /**
     * Add a XUI user
     */
    public function add()
    {
        $serverId = $this->request->getPost('server_id');
        $client = $this->request->getPost('client');
        $traffic = $this->request->getPost('traffic');

        // Validation
        if (!$serverId || !$client || !$traffic) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'SERVER_ID_CLIENT_AND_TRAFFIC_REQUIRED'
            ])->setStatusCode(400);
        }

        try {
            $xui = new XuiService((int)$serverId);
            $result = $xui->addUser($client, (int)$traffic);
            return $this->response->setJSON($result);
        } catch (\RuntimeException $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'error' => $e->getMessage()
            ])->setStatusCode(400);
        }
    }

    /**
     * Update a XUI user
     */
    public function update()
    {
        $serverId = $this->request->getPost('server_id');
        $client = $this->request->getPost('client');
        $mode = $this->request->getPost('mode');
        $value = $this->request->getPost('value');

        if (!$serverId || !$client || !$mode) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'SERVER_ID_CLIENT_AND_MODE_REQUIRED'
            ])->setStatusCode(400);
        }

        try {
            $xui = new XuiService((int)$serverId);
            return $this->response->setJSON($xui->updateUser($client, $mode, $value));
        } catch (\RuntimeException $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'error' => $e->getMessage()
            ])->setStatusCode(400);
        }
    }

    /**
     * Delete a XUI user
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
            $xui = new XuiService((int)$serverId);
            return $this->response->setJSON($xui->deleteUser($client));
        } catch (\RuntimeException $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'error' => $e->getMessage()
            ])->setStatusCode(400);
        }
    }

    /**
     * List all XUI users for a server
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
            $xui = new XuiService((int)$serverId);
            return $this->response->setJSON($xui->listUsers(true));
        } catch (\RuntimeException $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'error' => $e->getMessage()
            ])->setStatusCode(400);
        }
    }

    /**
     * Download QR code for a client
     */
    public function download(int $serverId, string $clientName)
    {
        try {
            $xui = new XuiService($serverId);
            $path = $xui->getFilePath($clientName);

            if (!$path) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'error' => 'QR_NOT_FOUND'
                ])->setStatusCode(404);
            }

            return $this->response->download($path, null)->inline();
        } catch (\RuntimeException $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'error' => $e->getMessage()
            ])->setStatusCode(400);
        }
    }
}