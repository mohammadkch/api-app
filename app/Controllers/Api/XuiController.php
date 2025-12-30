<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\VpnServer\XuiService;

class XuiController extends BaseController
{
    private XuiService $xui;

    public function __construct()
    {
        // IP server x-ui
        $this->xui = new XuiService('5.161.144.182');
    }

    /**
     * Add x-ui user
     */
    public function add()
    {
        $result = $this->xui->addUser();

        return $this->response->setJSON($result);
    }
}
