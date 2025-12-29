<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\VpnServer\OpenVpnService;

class OpenVpnController extends BaseController
{
    private OpenVpnService $vpn;

    public function __construct()
    {
        $this->vpn = new OpenVpnService('5.161.144.182');
    }

    /**
     * Add a VPN client
     */
    public function add()
    {
        $client = $this->request->getPost('client');

        if (!$client) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'CLIENT_REQUIRED'
            ])->setStatusCode(400);
        }

        $result = $this->vpn->addClient($client);

        // Optionally auto-download the config after creation
        if (($result['status'] ?? '') === 'ok') {
            $download = $this->vpn->downloadConfig($client);
            $result['downloaded'] = $download;
        }

        return $this->response->setJSON($result);
    }

    /**
     * Delete (revoke) a VPN client
     */
    public function delete()
    {
        $client = $this->request->getPost('client');

        if (!$client) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'CLIENT_REQUIRED'
            ])->setStatusCode(400);
        }

        $result = $this->vpn->deleteClient($client);

        $localFile = WRITEPATH . "storage/openvpn/{$client}.ovpn";
        if (file_exists($localFile)) {
            unlink($localFile);
            $result['local_deleted'] = true;
        } else {
            $result['local_deleted'] = false;
        }

        return $this->response->setJSON($result);
    }

    /**
     * Download an existing client's .ovpn file
     */
    public function download()
    {
        $client = $this->request->getPost('client');

        if (!$client) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'CLIENT_REQUIRED'
            ])->setStatusCode(400);
        }

        $result = $this->vpn->downloadConfig($client);

        return $this->response->setJSON($result);
    }
}
