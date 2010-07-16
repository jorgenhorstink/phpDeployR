<?php

/**
 * 
 * @author Henk Erik van der Hoek (mail@henkerikvanderhoek.nl)
 * @author Jorgen Horstink (mail@jorgenhorstink.nl)
 *
 * @version 1.0
 */

require_once COLLECTION_DIRECTORY . '/Map.php';

class ArrayMap implements Map {
    
    protected $map = array ();
    
	
	public function put ($key, $value)
	{
	    $this->map[$key] = $value;	    
	}
	
	public function get ($key)
	{
	    if ($this->containsKey ($key))
	        return $this->map[$key];

	    return null;
	}
	
	public function containsKey ($key)
	{
	    return isset ($this->map[$key]);
	}
	
	/**
	 * @return Iterator
	 */
	public function getIterator ()
	{
	    return new ArrayObject($this->map);
	}
	
	public function isEmpty ()
	{
	    return $this->length() == 0;
	}
    
    public function join ($separator)
    {
        return join($separator, $this->map);
    }
    
    public function length()
    {
        return sizeof($this->map);   
    }
    
    public function remove($key) {
        if($this->containsKey($key)) {
            unset($this->map[$key]);
        }   
    }
    
    public function clear()
    {
        $this->map = array();
    }
    
}


?>