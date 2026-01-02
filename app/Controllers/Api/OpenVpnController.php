<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\VpnServer\OpenVpnService;
use http\Env\Response;

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
            return $this->response->setJSON(['status' => 'error', 'error' => 'CLIENT_REQUIRED'])->setStatusCode(400);
        }

        $result = $this->vpn->addClient($client);

        if (($result['status'] ?? '') === 'ok') {
            $download = $this->vpn->downloadConfig($client);

            if ($download['status'] === 'ok') {
                // This is the link your bot will use to get the file
                $result['config_url'] = base_url("api/ovpn/download/{$client}");
            }
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

        return $this->response->setJSON($result);
    }

    /**
     * List all VPN clients
     */
    public function list()
    {
        return $this->response->setJSON(
            $this->vpn->listClients()
        );
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

    public function getFile(string $clientName)
    {
        $path = WRITEPATH . "storage/openvpn/{$clientName}.ovpn";

        if (!file_exists($path)) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'FILE_NOT_FOUND'
            ])->setStatusCode(404);
        }

        return $this->response->download($path, null)->setFileName("{$clientName}.ovpn");
    }
}
