<?php

class AppUtil
{
    
    public static function getSession()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $sess = array();
        if (isset($_SESSION['uid'])) {
            $sess["uid"]   = $_SESSION['uid'];
            $sess["name"]  = $_SESSION['name'];
            $sess["email"] = $_SESSION['email'];
        } else {
            $sess["uid"]   = '';
            $sess["name"]  = 'Guest';
            $sess["email"] = '';
        }
        return $sess;
    }
    
    public static function destroySession()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION['uid'])) {
            unset($_SESSION['uid']);
            unset($_SESSION['name']);
            unset($_SESSION['email']);
            // $info = 'info';
            // if (isset($_COOKIE[$info])) {
            //     setcookie($info, '', time() - $cookie_time);
            // }
            session_destroy();
            $msg = "Logged Out Successfully...";
        } else {
            $msg = "Not logged in...";
        }
        return $msg;
    }
    
}

?>
