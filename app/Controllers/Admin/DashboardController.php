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
}