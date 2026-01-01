<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\VpnServer\XuiService;

class XuiController extends BaseController
{
    private XuiService $xui;

    public function __construct()
    {
        $this->xui = new XuiService('5.161.144.182');
    }

    public function add()
    {
        $client = $this->request->getPost('client');
        $traffic = $this->request->getPost('traffic');

        if (!$client) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'CLIENT_REQUIRED'
            ])->setStatusCode(400);
        }

        $result = $this->xui->addUser($client, $traffic);

        return $this->response->setJSON($result);
    }

    public function list()
    {
        $inactive = $this->request->getGet('inactive') === '1';

        return $this->response->setJSON(
            $this->xui->listUsers($inactive)
        );
    }

    public function update()
    {
        $client = $this->request->getPost('client');
        $mode   = $this->request->getPost('mode');
        $value  = $this->request->getPost('value');

        if (!$client || !$mode) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'CLIENT_OR_MODE_REQUIRED'
            ])->setStatusCode(400);
        }

        return $this->response->setJSON(
            $this->xui->updateUser($client, $mode, $value)
        );
    }

    public function delete()
    {
        $client = $this->request->getPost('client');

        if (!$client) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'CLIENT_REQUIRED'
            ])->setStatusCode(400);
        }

        return $this->response->setJSON(
            $this->xui->deleteUser($client)
        );
    }

    public function qr()
    {
        $client = $this->request->getPost('client');

        if (!$client) {
            return $this->response->setJSON([
                'status' => 'error',
                'error' => 'CLIENT_REQUIRED'
            ])->setStatusCode(400);
        }

        $info = $this->xui->getClientInfo($client);

        if (($info['status'] ?? '') !== 'ok') {
            return $this->response->setJSON($info);
        }

        $link = $info['config'] ?? null;

        if (!$link) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'NO_CONFIG_LINK'
            ]);
        }

        $result = $this->xui->generateQrCode($client, $link);

        return $this->response->setJSON(array_merge($info, $result));
    }
}
