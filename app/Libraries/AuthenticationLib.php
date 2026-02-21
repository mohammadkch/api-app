<?php

namespace App\Libraries ;

class AuthenticationLib {

    private $session ;
    private $userID = null ;

    private $controllerName = null ;
    private $methodName = null ;
    private $className = null ;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function login( $userID, $additionalData = [] )
    {
        $additionalData['userID'] = $userID ;
        $this->session->set( $additionalData );

        return TRUE ;
    }

    public function logout()
    {
        $this->session->remove( ['userID'] );

        $this->userID = NULL ;

        return TRUE ;
    }

    public function isLoggedIn()
    {
        $sessionUserID = $this->session->get('userID');

        if (! empty( $sessionUserID)) {
            if ( $sessionUserID > 0 ) {
                $this->userID = $sessionUserID ;

                return TRUE ;
            }
        }



        return FALSE ;
    }

    public function setControllerName( $controllerName )
    {
        $this->controllerName = $controllerName ;
    }

    public function setMethodName( $methodName )
    {
        $this->methodName = $methodName ;
    }

    public function setClassName( $className )
    {
        $this->className = $className ;
    }

    public function getUserID()
    {
        return $this->userID ;
    }

    public function getControllerName()
    {
        return $this->controllerName ;
    }

    public function getMethodName()
    {
        return $this->methodName ;
    }

    public function getClassName()
    {
        return $this->className ;
    }

    public function getLoginData( $key )
    {
        return $this->session->get( $key );
    }

}