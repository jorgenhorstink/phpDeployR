<?php

interface Session
{
    const DEFAULT_NAMESPACE = 'global';
    
    public function getAttribute($name, $ns = Session::DEFAULT_NAMESPACE);
    
    public function setAttribute($name, $value, $ns = Session::DEFAULT_NAMESPACE);
    
    public function hasAttribute($name, $ns = Session::DEFAULT_NAMESPACE);
    
    public function removeAttribute($name, $ns = Session::DEFAULT_NAMESPACE);
}

?>