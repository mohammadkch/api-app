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

        return $this->response->setJSON(
            $this->xui->addUser($client)
        );
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

        return $this->response->setJSON(
            $this->xui->updateUser($client, $mode, $value)
        );
    }

    public function delete()
    {
        $client = $this->request->getPost('client');

        return $this->response->setJSON(
            $this->xui->deleteUser($client)
        );
    }
}
