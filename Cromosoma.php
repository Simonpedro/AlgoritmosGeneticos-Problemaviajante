<?php

include_once 'Provincia.php';
include_once 'DistanciaService.php';
/**
 * Description of Cromosoma
 *
 * @author Pedro
 */
class Cromosoma {

    /**
     *
     * @var array|int
     */
    protected $provincias;
    
    
    protected $distancia;
    
    protected $fitness;
    

    public function __construct(array $provincias = []) {
        $this->provincias = $provincias;
    }
    
    /**
     * 
     * @return array|Provincia
     */
    public function getProvincias() {
        return $this->provincias;
    }
    
    public function getDistancia() {
        return $this->distancia;
    }


    public function setDistancia($distancia) {
        $this->distancia = $distancia;
    }
    

    public function getFitness() {
        return $this->fitness;
    }

    public function setFitness($fitness) {
        $this->fitness = $fitness;
    }
    
    public function setProvincias(array $provincias) {
        $this->provincias = $provincias;
    }

    
}
