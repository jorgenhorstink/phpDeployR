<?php

class SessionFactory {
    
    protected static $instance;
    
    public static function getInstance () 
    {
        if (self::$instance == null) 
            throw new Exception ('No instance available');       
        
        return self::$instance;
    }
    
    public static function setInstance (Session $instance)
    {
        self::$instance = $instance;
    }
}

?>