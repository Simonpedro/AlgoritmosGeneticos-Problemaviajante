<?php

include_once 'AlgoritmoBase.php';
/**
 * Description of Exhaustivo
 *
 * @author Pedro
 */
class Exhaustivo extends AlgoritmoBase{

    protected $mejorRuta = [];
    
    protected $mejorDistancia = PHP_INT_MAX;
    
    protected $nodoInicial;
    
    protected $time = 0;


    public function __construct() {
        $this->limiteNodo = 4;
        parent::__construct();

    }
    
    /**
     * En clave 'mejorRuta' se devuelve array de provincias
     * En clave 'mejorDistancia' se devuelve la distancia total
     * 
     * @return array 
     */
    public function correr() {
        foreach ($this->nodos as $nodo) {
            $this->ruta = [];
            $this->nodoInicial = $nodo;
            $nodoInicial = clone $nodo;
            $nodoInicial->setEstado(true);

            $this->buscarRuta($nodoInicial);   
        }

        
        $mejorRuta = $this->toArrayProvincias($this->mejorRuta);
        return [
            'mejorRuta' => $mejorRuta,
            'mejorDistancia' => $this->mejorDistancia
        ];
    }
    
    protected function buscarRuta(Nodo $nodo) {
        $this->time++;
        $this->ruta[] = clone $nodo;
        $nodo->visitado();
        // si la ruta no esta completa
        $rutaNoCompleta = !$this->rutaEstaCompleta();
        if ($rutaNoCompleta) {
            //si los hijos del nodo todavia no se generaron
            if (!$nodo->hijosYaGenerados()) {
                $hijosNodo = $this->generarHijosNodo();
                $nodo->setHijos($hijosNodo);
            }
            $nodoHijo = $nodo->getHijoNoVisitado();
            //si tiene algun hijo no visitado vuelve a ejectura la funcion con el hijo
            if (!is_null($nodoHijo)) {
                return $this->buscarRuta($nodoHijo);
            } else {
                //Cuando no hay mas visitados es porque se recorrieron todos los del subarbol
                $nodo->eliminarHijos();
                array_pop($this->ruta);
                $nodoPadre = $nodo->getPadre();
                if ($nodoPadre === null) {
                   return;
                }
                array_pop($this->ruta); //saca de la ruta porque se vuelve a agregar despues
                $nodoPadre->eliminarHijo($nodo);
                return $this->buscarRuta($nodoPadre);
            }
            
        } else {
            //cuando no se pueden crear hijos es porque se recorrieron todos los nodos
            $this->ruta[] = $this->nodoInicial; //porque debe volver a la ciudad de partida
            $distanciaRuta = $this->distanciaService->getDistancia($this->ruta);
            if ($distanciaRuta < $this->mejorDistancia) {
                $this->mejorDistancia = $distanciaRuta;
                $this->mejorRuta = $this->ruta;
            }
            //$this->imprimirResultados($distanciaRuta);
            array_pop($this->ruta); //una vez para sacar el nodo inicial
            array_pop($this->ruta); //y otra para sacar el ultimo nodo de la ruta   
            $nodoPadre = $nodo->getPadre();
            if (!is_null($nodoPadre)) {
                array_pop($this->ruta); //otra vez para sacar el nodo padra, porque despues se vuelve a agregar
                $nodoPadre->eliminarHijo($nodo);
                return $this->buscarRuta($nodoPadre);
            }
            return false;
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
        echo "distancia total = $distanciaRuta" ;
        echo '<br>';
    }

}
