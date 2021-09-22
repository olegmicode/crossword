<?php

namespace App\Auth;

class Util
{
    public function checkAuth()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/admin/');
        }
    }

    public function isLoggedIn()
    {
        if (!empty($_SESSION["user_id"])) {
            return true;
        }
        return false;
    }

    public function redirect($url)
    {
        header("Location:" . $url);
        exit;
    }

    public function clearAuthCookie()
    {
        if (isset($_COOKIE["member_login"])) {
            setcookie("member_login", "");
        }
        if (isset($_COOKIE["random_password"])) {
            setcookie("random_password", "");
        }
        if (isset($_COOKIE["random_selector"])) {
            setcookie("random_selector", "");
        }
    }
}