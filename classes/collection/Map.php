<?php

/**
 * 
 * @author Jorgen Horstink (mail@jorgenhorstink.nl)
 * @author Henk Erik van der Hoek (mail@henkerikvanderhoek.nl)
 *
 * @version 1.0
 */

interface Map extends IteratorAggregate {
    
	public function put ($key, $value);
	
	public function get ($key);
	
	public function containsKey ($key);
	
	public function isEmpty ();
	
	public function length ();
}

?>