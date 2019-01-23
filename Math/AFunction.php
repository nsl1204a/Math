<?php

//namespace AppBundle\Math;

//use AppBundle\Math\Progression;

/* 
 * Esta clase representa una funcion analítica que debe ser extendida. 
 * Por si misma implementa la funcion identidad y = x
 */
class AFunction
{
 
    Const P2P = 1;
    
    protected $name;

    
    public function __construct ($name){
        
        $this->name = $name;
        
    }
    
    public function f($x){
        return $x;
    }

}