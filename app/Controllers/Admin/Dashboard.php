<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        return view( $this->viewPath . 'admin/index' , $this->viewData );
//        return view( $this->viewPath . 'dashboard/index-main' , $this->viewData );
    }
}