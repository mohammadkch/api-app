<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\VpnServer\OpenVpnManager;

class OpenVpnController extends BaseController
{
    private OpenVpnManager $vpn;

    public function __construct()
    {
        $this->vpn = new OpenVpnManager();
    }

    public function add()
    {
        $client = $this->request->getPost('client');

        if (!$client) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'CLIENT_REQUIRED',
            ]);
        }

        $output = $this->vpn->addClient($client);

        // output خودش __RESULT__ داره، همونو برمی‌گردونیم
        return $this->response
            ->setContentType('application/json')
            ->setBody($output);
    }

    public function delete()
    {
        $client = $this->request->getPost('client');

        if (!$client) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'CLIENT_REQUIRED',
            ]);
        }

        $output = $this->vpn->deleteClient($client);

        return $this->response
            ->setContentType('application/json')
            ->setBody($output);
    }
}
