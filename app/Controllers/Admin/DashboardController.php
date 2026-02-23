<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        return view( $this->viewPath . 'dashboard/index' , $this->viewData );
//        return view( $this->viewPath . 'dashboard/index-main' , $this->viewData );
    }

    public function logout()
    {
        $logout = $this->authLib->logout() ;

        if ( $logout == true ) {
            return redirect()->to('admin/login');
        }
        else {
            return redirect()->to('admin/dashboard');
        }
    }
}