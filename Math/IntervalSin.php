<?php

//namespace AppBundle\Math;

//use AppBundle\Math\Interval;

include_once 'Interval.php';

class IntervalSin extends Interval
{

    /**
    * Ejemplo de uso 
    */
    public static function main (){

        $sin = new self(Interval::P2P, -10, 10);
        echo "punto ({$sin->getXi()},{$sin->getXf()})\n";  
        $apoints = $sin->run(null, 0.1, true, true);
        print_r($apoints);
    }

    /**
     * funcion seno
     */
    public function f($x){
        
        return sin($x);
        
    }
}

IntervalSin::main();