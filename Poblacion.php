<?php

include_once 'Cromosoma.php';


class Poblacion {
    
    public static $cantCromosomas;
    
    /**
     *
     * @var DistanciaService
     */
    public static $ds;
    
    /**
     *
     * @var array|Cromosoma
     */
    protected $cromosomas;
    
    protected $mejorCromosoma;

    protected $mejorDistancia;
    
    protected $peorDistancia;
    
    protected $distanciaTotal;
    
    protected $distanciaPromedio;


    public function __construct(array $cromosomas) {
        $this->cromosomas = $cromosomas;
    }

    public function evaluarCromosomas() {
        $distanciaAcum = 0;
        $mejorCromosoma = null;
        $mejorDistancia = PHP_INT_MAX;
        $peorDistancia = 0;
        foreach ($this->cromosomas as $cromosoma) {
            //evaluo las distancia para los cromosomas
            $provincias = $cromosoma->getProvincias();
            $provincias[] = $provincias[0]; //para tener en cuenta que vuelve al punto inicial
            $distanciaRuta = self::$ds->getDistanciaProvincias($provincias);
            $distanciaAcum += $distanciaRuta;
            $cromosoma->setDistancia($distanciaRuta);
            //logica para obtener mejor distancia
            if ($distanciaRuta < $mejorDistancia) {
                $mejorDistancia = $distanciaRuta;
                $mejorCromosoma = $cromosoma;
            }
            //logica para obtener peor distancia
            if ($distanciaRuta > $peorDistancia) {
                $peorDistancia = $distanciaRuta;
            }
        }
        $this->mejorCromosoma = $mejorCromosoma;
        $this->mejorDistancia = $mejorDistancia;
        $this->peorDistancia  = $peorDistancia;
        $this->distanciaPromedio = $distanciaAcum / self::$cantCromosomas;
        $this->distanciaTotal = $distanciaAcum;
        $this->calcularFitness();
    }
    
    public function getMejorCromosoma() {
        return $this->mejorCromosoma;
    }
    
    public function getCromosomas() {
        return $this->cromosomas;
    }
    
    public function getDistanciaTotal() {
        return $this->distanciaTotal;
    }

    private function calcularFitness() {
        foreach ($this->cromosomas as $c) {
            $fitness = 1 − $c->getDistancia() / $this->distanciaTotal;
            $c->setFitness($fitness);
        }
    }
    
    public function getMejorDistancia() {
        return $this->mejorDistancia;
    }

    public function getPeorDistancia() {
        return $this->peorDistancia;
    }
    
    public function getDistanciaPromedio() {
        return $this->distanciaPromedio;
    }
    
    public function setMejorCromosoma($mejorCromosoma) {
        $this->mejorCromosoma = $mejorCromosoma;
    }

}
