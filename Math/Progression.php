<?php

//namespace AppBundle\Math;

/**
 * La clase progression es una progresion matematica que determina el ritmo de avance al limite
 * en calculos iterativos. Por ejemplo determina la evolucion de acortamiento de intervalo de abcisa
 * en el calculo numerico de la Integral de Rieman o de la derivada.
 */
class Progression 
{

    /*
     * tipo de sucesion: 
     * 0: atirmetica: a0 + a2(n-1)
     * 1: cuadratica: a0 + a1.(n-1) + a2.(n-1)^(2)
     * 2: geometica:  a1.a2^(n-1)
     */
    Const ARITMETIC = 0;
    Const QUADRATIC = 1;
    Const GEOMETRIC = 2 ;

    private $type;  
    private $term;  //a0
    private $coeff; //a1
    private $step; //a2

    //valores actuales de la sucesion
    private $position;
    private $value;

    
    /*
    static function OnAritmetic($term, $step) {
        return new self(self::ARITMETIC, [$term,0,$step]);
    }

    static function OnGeometric($term, $coeff, $step) {
        return new self(self::GEOMETRIC, [$term,$coeff,$step]);
    }
    */ 
    
    /*
     * Ejemplo
     */
    public static function main() {

        //$pro = new self(self::GEOMETRIC,0, 5, 0.5);
        //$pro = new self(self::ARITMETIC, -5, 0, 0.5);
        $pro = new self(self::QUADRATIC, 5, 0, -0.05);
        
        for ($i=0;$i<10;$i++){
            $aval = $pro->getCurrent();
            echo "posicion: $aval[0] valor: $aval[1]\n";
            $pro->next();
        }
        
        /*
        for ($i=1;$i<11;$i++){
            $aval = $pro->getValue($i);
            echo "posicion: $aval[0] valor: $aval[1]\n";
        }
         */
        
    }

    /**
     * Crea una progresion con el tipo y array de parametros [a0, a1, a2]
     * para el termino independiente, el coeficiente y la razón respectivamente.
     */
    public function __construct ($type = self::ARITMETIC, $term, $coeff, $step){

        $this->type = $type;
        
        if($this->type != self::ARITMETIC && 
           $this->type != self::QUADRATIC && 
           $this->type != self::GEOMETRIC) {

            $this->type = self::ARITMETIC;
        }

        $this->term  = $term;
        $this->coeff = $coeff;
        $this->step = $step;
        
        $this->setPosition(1);
    }

    /**
     * obtiene tipo de sucesion
     */
    public function getType(){
        return $this->type;
    }
    
    /**
    * obtener la posicion y el valor actual de la sucesion
    */
    public function getCurrent(){

        return [$this->position,round($this->value,13)];
        
    }

    /**
     * establecer la posicion de la sucesion 
    */
    public function setPosition($position){

        $ares = $this->getValue($position);
        $this->position = $ares[0];
        $this->value = $ares[1];
        
    }

    /**
     * obtener el valor de la sucesion para una posicion determinada
     */
    public function getValue($position){

        if ($position < 1) {
            $position = 1;
        }

        $pick = $position - 1;    
        
        switch($this->getType()){

            case self::ARITMETIC:
                $value = $this->term + ($this->step * $pick );
                break;

            case self::GEOMETRIC:
                $value = $this->term + $this->coeff * ($this->step ** $pick);
                break;

            case self::QUADRATIC:
                $value = $this->term + ($this->coeff * $pick) + ($this->step * ($pick ** 2));
                break;
        }

        return [$position,round($value,13)];
    }

    /**
    * valor siguiente de la sucesion.
    */
    public function next(){

        /* si no hay posicion establecida se pone la 1 */
        
        if (!$this->position) {
            $this->setPosition(1);
            return;
        }

        switch($this->getType()){

            case self::ARITMETIC:
                $this->value += $this->step;    
                break;

            case self::GEOMETRIC:
                $this->value = $this->term + ($this->step * ($this->value - $this->term));
                break;

            case self::QUADRATIC:
                //$this->value = ( ($this->value / $this->position)* (2 + $this->position)) + 1;
                $pick = $this->position - 1;    
                $this->value += $this->coeff + ($this->step)*( (2 * $pick) + 1);
                break;
        }

        $this->position += 1;
    }

}

// Progression::main();