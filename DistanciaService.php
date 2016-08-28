<?php

/**
 * Description of DistanciaService
 *
 * @author Pedro
 */
class DistanciaService {

    //put your code here

    protected $matrizDistancia;

    public function __construct() {
        $this->matrizDistancia = $this->crearMatrizDistancia();
    }

    private function crearMatrizDistancia() {
        $matriz = [];
        $handle = fopen("datos/distancias.csv", "r");
        if ($handle) {
            while (($line = fgets($handle) ) !== false) {
                $matriz[] = split(";", $line);
            }
        } else {
            echo "no se pudo abrir el archivo";
        }
        fclose($handle);
        return $matriz;
    }

    public function getDistanciaBetween($id1, $id2) {
        if (is_null($id1) || is_null($id2)) {
            $a = 1;
            exit;
        }
        $distancia = $this->matrizDistancia[$id1][$id2];
        return floatval($distancia);
    }

    /**
     * 
     * @param array|Nodo $nodos
     */
    public function getDistancia(array $nodos) {
        $distanciaTotal = 0;
        for ($index = 0; $index < count($nodos) - 1; $index++) {
            $idProvinicia1 = $nodos[$index]->getProvincia()->getId();
            $idProvinicia2 = $nodos[$index + 1]->getProvincia()->getId();
            $distanciaTotal += floatval($this->getDistanciaBetween($idProvinicia1, $idProvinicia2));
        }
        return $distanciaTotal;
    }

    /**
     * 
     * @param array|int $provincias
     */
    public function getDistanciaProvincias($provincias) {
        $distanciaTotal = 0;
        for ($index = 0; $index < count($provincias) - 1; $index++) {
            $idProvinicia1 = $provincias[$index];
            $idProvinicia2 = $provincias[$index + 1];
            $distanciaTotal += floatval($this->getDistanciaBetween($idProvinicia1, $idProvinicia2));
        }
        return $distanciaTotal;
    }

    public function getMatrizDistancia() {
        return $this->matrizDistancia;
    }

    /**
     * 
     * @param Nodo $nodo
     * @return Nodo $hijo
     */
    public function getHijoMasCercano(Nodo $nodo) {
        $idProvinciaPartida = $nodo->getProvincia()->getId();
        $mejorHijo = null;
        $mejorDistancia = PHP_INT_MAX;
        $hijosNodo = $nodo->getHijos();
        foreach ($hijosNodo as $nodoHijo) {
            $idProvinciaDestino = $nodoHijo->getProvincia()->getId();
            $distancia = $this->getDistanciaBetween($idProvinciaPartida, $idProvinciaDestino);
            if ($distancia < $mejorDistancia) {
                $mejorDistancia = $distancia;
                $mejorHijo = $nodoHijo;
            }
        }
        return $mejorHijo;
    }

}
