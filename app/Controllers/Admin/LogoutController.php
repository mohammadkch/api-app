<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class LogoutController extends BaseController
{
    public function index()
    {
        service('Authentication')->logout();
        return redirect()->to('/admin/login')->with('message', lang('Auth.successLogout'));
    }

}
