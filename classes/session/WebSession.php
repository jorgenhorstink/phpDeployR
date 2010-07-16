<?php

require_once SESSION_DIRECTORY . '/Session.php';

class WebSession implements Session
{
    protected static $instance;
    
    protected function __construct() {
        // Start a session if not present
        if(!session_id ()) {
            session_start ();
        }
    }
    
    public static function getInstance ()
    {
        if (self::$instance == null)
            self::$instance = new WebSession();
        
        return self::$instance;
    }

    public function getAttribute ($name, $ns = Session::DEFAULT_NAMESPACE)
    {
        if ($this->hasAttribute ($name, $ns)) {
            return $_SESSION[$ns][$name];
        } else {
            return null;
        }
    }

    public function setAttribute ($name, $value, $ns = Session::DEFAULT_NAMESPACE)
    {
        $_SESSION[$ns][$name] = $value;
    }

    public function hasAttribute ($name, $ns = Session::DEFAULT_NAMESPACE)
    {
        return isset ($_SESSION[$ns][$name]);
    }
    
    public function removeAttribute($name, $ns = Session::DEFAULT_NAMESPACE)
    {
        unset($_SESSION[$ns][$name]);   
    }

    public function removeNamespace($ns = Session::DEFAULT_NAMESPACE)
    {
        unset($_SESSION[$ns]);
    }
}

?>