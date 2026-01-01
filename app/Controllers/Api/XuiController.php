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

        $result = $this->xui->addUser($client);

        if (($result['status'] ?? '') === 'ok') {
            $link = $result['config'] ?? null;
            if ($link) {
                try {
                    $qrResult = $this->xui->generateQrCode($client, $link);

                    if (($qrResult['status'] ?? '') === 'ok') {
                        $result['qr_status'] = 'ok';
                        $result['qr_path']   = $qrResult['path'];
                    } else {
                        $result['qr_status'] = 'failed';
                        $result['qr_error']  = $qrResult['message'] ?? $qrResult['error'] ?? 'UNKNOWN_ERROR';
                    }
                } catch (\Throwable $e) {
                    $result['qr_status'] = 'failed';
                    $result['qr_error']  = $e->getMessage();
                }
            } else {
                $result['qr_status'] = 'skipped';
                $result['qr_error']  = 'NO_CONFIG_LINK';
            }
        }

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

        try {
            $qrResult = $this->xui->generateQrCode($client, $link);

            if (($qrResult['status'] ?? '') === 'ok') {
                $result = [
                    'status' => 'ok',
                    'client' => $client,
                    'qr_status' => 'ok',
                    'qr_path' => $qrResult['path']
                ];
            } else {
                $result = [
                    'status' => 'ok',
                    'client' => $client,
                    'qr_status' => 'failed',
                    'qr_error' => $qrResult['message'] ?? $qrResult['error'] ?? 'UNKNOWN_ERROR'
                ];
            }
        } catch (\Throwable $e) {
            $result = [
                'status' => 'ok',
                'client' => $client,
                'qr_status' => 'failed',
                'qr_error' => $e->getMessage()
            ];
        }

        return $this->response->setJSON($result);
    }
}
