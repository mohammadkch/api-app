<?php

namespace App\Controllers\Admin;

class LogoutController extends BaseController
{

    public function index()
    {
        $this->authLib->logout();
        return redirect()->to('admin/login')->with('message', lang('Auth.successLogout'));

    }

}
