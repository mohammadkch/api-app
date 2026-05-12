<?php

namespace App\Controllers\Admin;

class DashboardController extends BaseController
{
    public function index()
    {
//
//        echo '<pre>';
//        print_r($this->viewData);
//        exit;

        return view( $this->viewPath . 'dashboard/index' , $this->viewData );
//        return view( $this->viewPath . 'dashboard/index-main' , $this->viewData );
    }

}