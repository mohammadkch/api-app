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

        if (!$client) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'CLIENT_REQUIRED',
            ])->setStatusCode(400);
        }

        $result = $this->xui->addUser($client);

        return $this->response->setJSON($result);
    }

    public function list()
    {
        $inactive = $this->request->getGet('inactive') === '1';

        $result = $this->xui->listUsers($inactive);

        return $this->response->setJSON($result);
    }

    public function update()
    {
        $client = $this->request->getPost('client');
        $mode   = $this->request->getPost('mode');
        $value  = $this->request->getPost('value');

        if (!$client || !$mode) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'CLIENT_OR_MODE_REQUIRED',
            ])->setStatusCode(400);
        }

        $result = $this->xui->updateUser($client, $mode, $value);

        return $this->response->setJSON($result);
    }

    public function delete()
    {
        $client = $this->request->getPost('client');

        if (!$client) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'CLIENT_REQUIRED',
            ])->setStatusCode(400);
        }

        $result = $this->xui->deleteUser($client);

        return $this->response->setJSON($result);
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
                'error' => 'NO_LINK_FOUND'
            ]);
        }

        $result = $this->xui->generateQrCode($client, $link);

        return $this->response->setJSON($result);
    }
}
