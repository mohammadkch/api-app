<?php

namespace App\Controllers\Admin;

class DashboardController extends BaseController
{
    public function index()
    {
        return view( $this->viewPath . 'dashboard/index' , $this->viewData );
//        return view( $this->viewPath . 'dashboard/index-main' , $this->viewData );
    }

}