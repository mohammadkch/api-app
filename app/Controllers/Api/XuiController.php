<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\VpnServer\XuiService;

class XuiController extends BaseController
{
    private XuiService $xui;

    public function __construct()
    {
        // پورت سرور XUI رو همینجا یکبار تعریف می‌کنی
        $this->xui = new XuiService('5.161.144.182', 2082);
    }

    public function add()
    {
        $client = $this->request->getPost('client');
        $traffic = $this->request->getPost('traffic');

        if (!$client || !$traffic) {
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'CLIENT_AND_TRAFFIC_REQUIRED'
            ])->setStatusCode(400);
        }

        $result = $this->xui->addUser($client, $traffic);

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

    public function list()
    {
        return $this->response->setJSON($this->xui->listUsers(true));
    }

    public function download($clientName)
    {
        $path = WRITEPATH . "storage/xui/{$clientName}.png";

        if (!file_exists($path)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'QR_NOT_FOUND'])->setStatusCode(404);
        }

        return $this->response->download($path, null)->inline();
    }

}
