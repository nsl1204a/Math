<?php

//namespace AppBundle\Math;

//use AppBundle\Math\Progression;

include_once 'Progression.php';

/* 
 * Esta clase representa un intervalo de abcisas
 */
class Interval
{
 
    Const P2P = 1;
    Const INCREMENT = 2;
    Const RADIUS = 3;
    
    protected $method;
    protected $increment;
    protected $sign;
    protected $xi;
    protected $xf;
    protected $progression;
    protected $function;

    /**
     * Ejemplo de uso 
     */
    public static function main(){
        
        $i3 = new self(Interval::P2P , 4, -4);
        echo "punto ({$i3->getXi()},{$i3->getXf()})\n";  
        $apoints = $i3->run(1.0, true, true);
        print_r($apoints);
        
    }
    
    public function __construct ($method, $x1, $x2){
        
        $this->xi = $x1;
        $this->method = $method;   
        $this->closed = TRUE;   
        
        if ($method == Interval::P2P) {
            $this->xf = $x2;
        }
        elseif ($method == Interval::INCREMENT) {
            $this->xf = $x1 + $x2;
        }
        elseif($method == Interval::RADIUS) {
            $this->xi = $x1 - $x2;
            $this->xf = $x1 + $x2;
        }
        
        $this->increment = $this->xf - $this->xi;
        
        if(!$this->increment) { 
            $this->sign  = 0;
        }
        else{
            $this->sign = $this->increment > 0 ? 1 : -1;
        }
        
    }
    
    public function getXi(){
        return $this->xi;
    }
    
    public function getXf(){
        return $this->xf;
    }    

    public function getMetohod(){
        return $this->method;
    }    
    
    public function getIncrement(){
        return $this->increment;
    }    

    public function setProgression($progression){
        $this->progression = $progression;
    }    

    public function getProgression(){
        return $this->progression;
    }    

    public function setFunction($aFunction){
        $this->function = $aFunction;
    }    
    
    public function getFunction(){    
        
        if ($this->function) {
            return array($this->function, 'f');
        } else {
            return array($this, 'f');
        }
    }

   
    /**
     * Recorre un intervalo desde xi a xf devolviendo los valores de una funcion.
     * Los extremos del intervalo pueden ser abiertos o cerrados
     * Creamos una progresion artimetica de razón (+/-)$step para avanzar por el intervalo
     * en la direccion que indica el sgino del intervalo: 
     * + hacia valores superiores 
     * - hacia valores inferiores
     */
    public function run($step, $closedBegin=true, $closedEnd=true){
        
        $pro = new Progression(Progression::ARITMETIC , $this->xi, null, $step * $this->sign);

        $funtion = $this->getFunction();
 
        //si el intervalo es abierto al inicio el primer valor de la suscesion,que es xi, se descarta
        if (!$closedBegin){
            $pro->next();
        }
        
        list($pos, $x) = $pro->getCurrent();
        
        $apoints = array();
        
        // en el bucle se trata el intervalo como si fuera abierto al final y no se calcula el punto xf
        while ($x != $this->xf) {
            $y =  call_user_func($funtion, $x);
            //echo "$pos -> ($x,$y)\n";
            $apoints[] = ['x' => $x, 'y' => $y];
            $pro->next();
            list($pos, $x) = $pro->getCurrent();
        }
        
        //Si el intervalo es cerrado al final se calcula tambien el valor en xf
        if ($closedEnd){
            $y =  call_user_func($funtion, $x);
            //echo "$pos -> ($x,$y)\n";
            $apoints[] = ['x' => $x, 'y' => $y];
        }
        
        return $apoints;
    }

    /**
     * Hay dos formas de implementar la funcion que tiene que ejecutar run:
     * 1 : extender la clase  Interval y redefinir la function f(x)
     * 2 : extender la clase AFunction, implementar f(x) y setear el intevalo con un objeto de esa clase: setFunction($aFunction)
     */
    protected function f($x){
        
        return $x;
        
    }
    
    /**
     * Obtener la derivada de una funcion en un punto de este intervalo. 
     * El punto se determina segun el metodo de definicion del intervalo. 
     * - En es un intervalo de radio r altededor de un punto x1, x1 es el punto
     * - En un intervalo de x1 a x2, x1 es el punto
     * - En un intervalo que parte de x1 y un incremento, x1 es el punto. 
     * 
     * En los metodos 2 y 3 se obtine la derivada lateral por la izquierda o la derecha. 
     * Solo el primer metodo obtiene una derivada centrada en el punto requerido. 
     * 
     * La progresion pasada sirve para establecer la forma de acercamiento al punto, por ejemplo, por mitades, seria una progresion geometrica de razón 0,5.  
     * 
     * @param type $x
     * @param type $progression
     */
    public function derivative($step, $progressionType = Progression::GEOMETRIC) {
        
        if(!$step){
            $step = 0.5;
        }
        
        switch ($progressionType) {
            case Progression::GEOMETRIC:
                //progreso a xi desde xi + incremento, con razon r < 0
                $pro = new Progression($progressionType,$this->xi, $this->increment, $step);
                break;
            case Progression::ARITMETIC:
                //progreso desde xf a xi, con razon r < 0
                $pro = new Progression($progressionType,$this->xf, 0, $this->sign * $step);
                break;
            case Progression::QUADRATIC:
                //progreso desde xf a xi, con razon r < 0
                $pro = new Progression($progressionType,$this->xf, 0, $this->sign * $step);
                break;
        }
        
        list($pos, $x) = $pro->getCurrent();
        
        
        
    }
    
    
}


Interval::main();