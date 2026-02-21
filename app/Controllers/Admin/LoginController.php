<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class LoginController extends BaseController
{
    public function index(): string
    {
        $msg = (int)$this->request->getVar("msg", FILTER_VALIDATE_INT);
        $msg_text = [
            '1' => 'نام کاربری یا گذرواژه اشتباه است.',
            '2' => 'هنگام ورود خطایی رخ داده است.',
            '3' => 'هنگام ورود خطایی رخ داده است.',
        ];

        $this->viewData['msg_text'] = isset($msg_text[$msg]) ? $msg_text[$msg] : null;

        return view($this->viewPath . 'auth/login');
    }

    public function authenticate()
    {
        $userModel = model('App\Models\UserModel');
        helper('sanitize');

        $username = $this->request->getPost('username', FILTER_CALLBACK, ['options' => 'sanitizeStripTags']);
        $password = $this->request->getPost('password', FILTER_CALLBACK, ['options' => 'sanitizeStripTags']);

        $user_rowset = $userModel->getUser([
            'username' => $username,
            'password' => $password
        ]);


        if (count($user_rowset) != 1) {
            return redirect()->to('admin/login?msg=1');
        }

        $user_id = (int)$user_rowset[0]['id'];
        $user_fullname = $user_rowset[0]['fullname'];

        if ($user_id < 1) {
            return redirect()->to('admin/login?msg=2');
        }

        $login_result = $this->authLib->login($user_id, ['fullname' => $user_fullname]);

        if ($login_result) {
            return redirect()->to('admin/dashboard');
        }

        return redirect()->to('login?msg=3');

    }
}
