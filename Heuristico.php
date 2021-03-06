<?php

include_once 'AlgoritmoBase.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Heuristico
 *
 * @author Pedro
 */
class Heuristico extends AlgoritmoBase {

    protected $mejorRuta = [];
    protected $mejorDistancia = PHP_INT_MAX;
    protected $nodoInicial;

    public function __construct() {
        parent::__construct();
    }

    public function correr() {
        
        foreach ($this->nodos as $nodo) {
            $this->nodoInicial = $nodo;
            $this->buscarRuta($this->nodoInicial);
        }
        
        
        $mejorRuta = $this->toArrayProvincias($this->mejorRuta);
        return [
            'mejorRuta' => $mejorRuta,
            'mejorDistancia' => $this->mejorDistancia
        ];
    }

    protected function buscarRuta(Nodo $nodo) {
        $this->ruta[] = clone $nodo;
        // si la ruta no esta completa
        $rutaNoCompleta = !$this->rutaEstaCompleta();
        if ($rutaNoCompleta) {
            $hijosNodo = $this->generarHijosNodo();
            $nodo->setHijos($hijosNodo);
            
            $nodoHijo = $this->distanciaService->getHijoMasCercano($nodo);
            if (!is_null($nodoHijo)) {
                return $this->buscarRuta($nodoHijo);
            } else {
                // cuando seria null el hijo?
            }
        } else {
            //cuando no se pueden crear hijos es porque se recorrieron todos los nodos
            $this->ruta[] = $this->nodoInicial; //porque debe volver a la ciudad de partida
            $distanciaRuta = $this->distanciaService->getDistancia($this->ruta);
            if ($distanciaRuta < $this->mejorDistancia) {
                $this->mejorDistancia = $distanciaRuta;
                $this->mejorRuta = $this->ruta;
            }
            $this->ruta = [];
            return;
        }
    }

    /**
     * 
     * @return []|Nodo
     */
    public function generarHijosNodo() {
        $array = array_diff($this->nodos, $this->ruta);
        $array = $this->cloneArray($array);
        return count($array) > 0 ? $array : null;
    }

    /**
     * 
     * @param array|Nodo $nodos
     */
    public function toArrayProvincias(array $nodos) {
        $provincias = [];
        foreach ($nodos as $nodo) {
            $provincias[] = $nodo->getProvincia();
        }
        return $provincias;
    }

    private function rutaEstaCompleta() {
        return count($this->nodos) === count($this->ruta);
    }

    /**
     * 
     * @param array|Nodo $array
     * @return array
     */
    public function cloneArray($array) {
        $clone = [];
        foreach ($array as $key => $value) {
            $clone[$key] = clone $value;
        }
        return $clone;
    }

    public function imprimirResultados($distanciaRuta) {
        echo '<br>';

        foreach ($this->ruta as $nodo) {
            $nombreProvincia = $nodo->getProvincia()->getNombre();
            echo "-> $nombreProvincia";
        }
        echo '<br>';
        echo "distancia total = $distanciaRuta";
        echo '<br>';
    }

}
