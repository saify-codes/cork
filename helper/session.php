<?php
class Session
{

    public static function flash($key, $mesage, $alert_type = 'is-success')
    {
        $_SESSION[$key] = $mesage;
        $_SESSION['alert_type'] = $alert_type;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }
    
    public static function alert()
    {
        return $_SESSION['alert_type'] ?? NULL;
    }

    public static function get($key)
    {
        $message = $_SESSION[$key];
        unset($_SESSION[$key]);
        unset($_SESSION['alert_type']);
        return $message;
    }
}
