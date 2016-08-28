<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Grafo
 *
 * @author Pedro
 */
class Grafo {
    
    /**
     *
     * @var array|Nodo
     */
    protected $nodos;
    
    protected $distanciaService;

    public function __construct($nodos, $distanciaService) {
        $this->nodos = $nodos;
    }
    
    public function getNodo($id) {
        foreach ($this->nodos as $nodo) {
            if ($nodo->id == $id) {
                return $nodo;
            }
        }
        return null;
    }
    
    /**
     * 
     * @param Nodo $nodo
     * @return Nodo or null
     */
    public function getHijoNoVisitado($nodo) {
        foreach ($nodo->getHijos() as $hijo) {
            if (!$hijo->isVisitado()) {
                return $hijo;
            }
        }
        return null;
    }
    
    
    public function getNodos() {
        return $this->nodos;
    }

    /**
     * 
     * @param Nodo $nodo
     */
    public function setHijosAnodo($nodo) {
        $nodo->setVisitado();
        $nodo->setHijos($this->getNoVisitados());
    }

    public function getNoVisitados() {
        
    }

}
