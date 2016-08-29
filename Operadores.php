<?php

include_once 'DistanciaService.php';

/**
 * Description of Operadores
 *
 * @author Pedro
 */
class Operadores {

    /**
     *
     * @var DistanciaService
     */
    protected static $ds;

    /**
     * 
     * @param Poblacion $poblacionInicial
     */
    public function seleccion($poblacionInicial) {
        $cromosomas = [];
        $cromosomas[] = $this->seleccionarCromosoma($poblacionInicial->getCromosomas());
        $cromosomas[] = $this->seleccionarCromosoma($poblacionInicial->getCromosomas());
        return $cromosomas;
    }

    /**
     * 
     * @param array|Cromosoma $cromosomas
     */
    private function seleccionarCromosoma($cromosomas) {
        $random = Util::random();
        $acumFitness = 0;
        foreach ($cromosomas as $cromosoma) {
            $acumFitness+= $cromosoma->getFitness();
            if ($acumFitness >= $random) {
                return $cromosoma;
            }
        }
    }

    /**
     * No esta bien, los que cambian deben completarse luego del foreach
     * 
     * @param Cromosoma $seleccionados
     */
    public function crossover($seleccionados) {

        $cromosoma1 = $seleccionados[0];
        $cromosoma2 = $seleccionados[1];

        $genes1 = $cromosoma1->getProvincias();
        $genes2 = $cromosoma2->getProvincias();

        $geni = $genes1[0];

        $needle = $genes2[0];

        $hijos1 = [];
        $hijos2 = [];
        $hijos1[0] = $genes1[0];
        $hijos2[0] = $genes2[0];

        $i = 0;

        while ($geni !== $needle) {
            $i++;
            if ($genes1[$i] === $needle) {
                $needle = $genes2[$i];
                $hijos1[$i] = $genes1[$i];
                $hijos2[$i] = $genes2[$i];
            }
            if ($i == 22) {
                $i = 0;
            }
        }

        for ($j = 0; $j < 23; $j++) {
            if (!array_key_exists($j, $hijos1)) {
                $hijos1[$j] = $genes1[$j];
                $hijos2[$j] = $genes2[$j];
            }
        }

        $cromosoma1->setProvincias($hijos1);
        $cromosoma2->setProvincias($hijos2);
        return [$cromosoma1, $cromosoma2];
    }

    /**
     * 
     * @param Cromosoma $hijo
     */
    public function mutacion($hijo) {
        $cromosoma = $hijo;

        $genes = $cromosoma->getProvincias();

        $random1 = mt_rand(0, 22);
        $random2 = mt_rand(0, 22);

        $gen1 = $genes[$random1];
        $genes[$random1] = $genes[$random2];
        $genes[$random2] = $gen1;

        $cromosoma->setProvincias($genes);
        return $cromosoma;
    }

}
