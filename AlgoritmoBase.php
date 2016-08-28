<?php
include_once "RepositorioProvincias.php";
include_once "DistanciaService.php";
include_once "Nodo.php";
/**
 * Description of AlgoritmoBase
 *
 * @author Pedro
 */
class AlgoritmoBase {
    
    /**
     *
     * @var DistanciaService
     */
    protected $distanciaService;
    
    /**
     *
     * @var RepositorioProvincias
     */
    protected $repoProvincias;
    
    /**
     *
     * @var []|Nodo 
     */
    protected $nodos;
    
    /**
     *
     * @var array|Nodo
     */
    protected $ruta = [];
    
    protected $limiteNodo = 100000;
    
    public function __construct() {
        $this->repoProvincias= new RepositorioProvincias();
        $this->nodos = $this->crearNodos($this->repoProvincias);
        $this->distanciaService = new DistanciaService();
        file_put_contents("datos/progreso.txt", " ");
    }

    private function crearNodos(RepositorioProvincias $provinciaRepo) {
        $nodos = [];
        $provincias = $provinciaRepo->getProvincias();
        
        foreach ($provincias as $key => $provincia) {
            $nodos[] = new Nodo($provincia);
            if ($key === $this->limiteNodo) {
                break;
            }
        }
        
        return $nodos;
    }
    
    protected function getNodo($idProvincia) {
        foreach ($this->nodos as $nodo) {
            $provinciaId = $nodo->getProvincia()->getId();
            if ($provinciaId == $idProvincia) {
                return $nodo;
            }
        }
        return null;
    }

}
