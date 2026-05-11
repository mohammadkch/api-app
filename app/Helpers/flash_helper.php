<?php

use App\Config\FlashMessages;

function setFlash($key, $customMessage = null)
{
    $message = FlashMessages::get($key, $customMessage);
    session()->setFlashdata('flash_type', $message['type']);
    session()->setFlashdata('flash_title', $message['title']);
    session()->setFlashdata('flash_message', $message['message']);
}

function getFlash()
{
    if (session()->has('flash_type')) {
        return [
            'type' => session()->get('flash_type'),
            'title' => session()->get('flash_title'),
            'message' => session()->get('flash_message')
        ];
    }
    return null;
}

function showFlash()
{
    $flash = getFlash();
    if ($flash) {
        echo "<script>showNotif('{$flash['type']}', '{$flash['title']}', '{$flash['message']}');</script>";
    }
}