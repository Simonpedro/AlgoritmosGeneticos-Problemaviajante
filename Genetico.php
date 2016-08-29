<?php

include_once 'AlgoritmoBase.php';
include_once 'Poblacion.php';
include_once 'Cromosoma.php';
include_once 'Operadores.php';
include_once 'Util.php';

/**
 * Description of Genetico
 *
 * @author Pedro
 */
class Genetico extends AlgoritmoBase {
    
    public static $mejorDistancia = PHP_INT_MAX;
    

    /**
     *
     * @var array|Provincia
     */
    protected $provincias;
    protected $cantPoblacionInicial;
    protected $ciclos;
    protected $probabilidadCrossover;
    protected $probabilidadMutacion;
    protected $operadores;
    protected $historialPoblaciones;
    
    protected $handlerArchivo;

    public function __construct(
    $probabilidadCrossover, $probabilidadMutacion, $cantPoblacionInicial = 51, $ciclos = 200
    ) {
        parent::__construct();
        file_put_contents("datos/progreso.txt", '0%');
        Poblacion::$ds = $this->distanciaService;
        Poblacion::$cantCromosomas = $cantPoblacionInicial;
        $this->provincias = $this->repoProvincias->getProvincias();
        $this->operadores = new Operadores();
        $this->cantPoblacionInicial = $cantPoblacionInicial;
        $this->ciclos = $ciclos;
        $this->probabilidadCrossover = $probabilidadCrossover;
        $this->probabilidadMutacion = $probabilidadMutacion;
        $this->handlerArchivo = fopen("datos/salidaGenetico.csv", "w");
    }

    public function correr() {
        $poblacion = $this->generarPoblacionInicial();
        $poblacion->evaluarCromosomas();

//        $this->historialPoblaciones[] = $poblacion;
        //para la cantidad de siclos dada generar nuevas poblaciones
        for ($i = 0; $i < $this->ciclos; $i++) {
            $poblacion = $this->generarNuevaPoblacion($poblacion);
            $poblacion->evaluarCromosomas();
//            $this->imprimirEstadisticos($poblacion);
            if ($i % 5 === 0) {
                           $progreso = 100 / $this->ciclos * $i;
            file_put_contents("datos/progreso.txt", round($progreso) . '%'); 
            }
//            $this->historialPoblaciones[] = $poblacion;
        }
        
        $mejorCromosoma = $poblacion->getMejorCromosoma();
        $mejorDistancia = $mejorCromosoma->getDistancia();
        $mejorRuta = $this->getProvincias($mejorCromosoma->getProvincias());
        $mejorRuta[] = $mejorRuta[0]; // para contemplar la partida, en el calculo de distancia ya se contempla
//        $this->mostrarResultados();
        fclose($this->handlerArchivo);
        return [
            'mejorRuta' => $mejorRuta,
            'mejorDistancia' => $mejorDistancia
        ];
    }

    /**
     * 
     * @return \Poblacion
     */
    private function generarPoblacionInicial() {
        $cromosomas = [];
        for ($index = 0; $index < $this->cantPoblacionInicial; $index++) {
            $provincias = $this->provincias;
            $genes = [];

            while (count($provincias) > 0) {
                $keys = array_keys($provincias);
                $random = mt_rand(0, count($provincias) - 1);
                $keyProvincia = $keys[$random];
                $prov = $provincias[$keyProvincia];
                $genes[] = $prov->getId();
                unset($provincias[$keyProvincia]);
            }
            $cromosomas[] = new Cromosoma($genes);
        }
        return new Poblacion($cromosomas);
    }

    /**
     * 
     * @param Poblacion $poblacion
     * @return \Poblacion
     */
    public function generarNuevaPoblacion($poblacion) {
        $cromosomasNuevos = [];
        $mejorCromosoma = clone $poblacion->getMejorCromosoma();
        for ($j = 0; $j < ($this->cantPoblacionInicial-1) / 2; $j++) {
            $seleccionados = $this->operadores->seleccion($poblacion);
            $hijos = [];
            $random = Util::random();
            if ($random < $this->probabilidadCrossover) {
                $temp = [];
                $temp = $this->operadores->crossover($seleccionados);
                $hijos[0] = $temp[0];
                $hijos[1] = $temp[1];
                $random = Util::random();
                if ($random < $this->probabilidadMutacion) {
                    $hijos[0] = $this->operadores->mutacion($hijos[0]); //deben ser otros objetos
                }//deben ser otros objeos
                $random = Util::random();
                if ($random < $this->probabilidadMutacion) {
                    $hijos[1] = $this->operadores->mutacion($hijos[1]); //deben ser otros objetos
                }//deben ser otros objeos
            } else {
                $hijos[0] = $seleccionados[0];
                $hijos[1] = $seleccionados[1];
            }
            $cromosomasNuevos[] = $hijos[0];
            $cromosomasNuevos[] = $hijos[1];
        }
        $cromosomasNuevos[] = $mejorCromosoma;
        $poblacionNueva = new Poblacion($cromosomasNuevos);
        return $poblacionNueva;
    }

    public function getProvincias(array $idsProvincias) {
        $provincias = [];
        foreach ($idsProvincias as $id) {
            $provincias[] = $this->repoProvincias->getProvincia($id);
        }
        return $provincias;
    }

    public function mostrarResultados() {
        foreach ($this->historialPoblaciones as $key => $poblacion) {
            echo 'POBLACION ' . $key . '<br>';
            $cromosomas = $poblacion->getCromosomas();
            foreach ($cromosomas as $cromosoma) {
                echo var_dump($cromosoma);
            }
        }
        echo '<br><br>';
    }

    /**
     * 
     * @param Poblacion $poblacion
     */
    public function imprimirEstadisticos($poblacion) {
//        echo var_dump($poblacion->getMejorCromosoma()) . '<br>';
        $linea = $poblacion->getMejorDistancia() . ';'
                .$poblacion->getDistanciaPromedio() . ';'
                .$poblacion->getPeorDistancia() . "\n";
        fwrite($this->handlerArchivo, $linea);
    }

}
