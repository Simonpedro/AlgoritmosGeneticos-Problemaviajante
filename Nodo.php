<?php



/**
 * Description of Nodo
 *
 * @author Pedro
 */
class Nodo {
    
    const VISITADO = true;
    const NO_VISITADO = false;


    /**
     *
     * @var bool
     */
    protected $hijosYaGenerados = false;
    
    /**
     *
     * @var Provincia
     */
    protected $provincia;
    
    /**
     *
     * @var Nodo
     */
    protected $padre = null;
    
    /**
     *
     * @var []|Nodo
     */
    protected $hijos = [];
    
    protected $estado = Nodo::NO_VISITADO;
    
    public function __construct(Provincia $provincia) {
        $this->provincia = $provincia;
    }
    
    public function __toString() {
        return (string) $this->provincia->getId();
    }
    
    // <editor-fold defaultstate="collapsed" desc="Getters & Setters">
    /**
     * 
     * @return Provincia
     */
    public function getProvincia() {
        return $this->provincia;
    }

    /**
     * 
     * @return int
     */
    public function getEstado() {
        return $this->estado;
    }

    public function setProvincia($provincia) {
        $this->provincia = $provincia;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }
    
    public function getHijos() {
        return $this->hijos;
    }

    public function setHijos(array $hijos) {
        $this->hijos = $hijos;
        foreach ($this->hijos as $hijo) {
            $hijo->setPadre($this);
        }
        $this->hijosYaGenerados = true;
    }
    
    public function getPadre() {
        return $this->padre;
    }

    public function setPadre(Nodo $padre) {
        $this->padre = $padre;
    }

    // </editor-fold>

    public function visitado() {
        $this->estado = self::VISITADO;
        return $this;
    }
    
    public function noVisitado() {
        $this->estado = self::NO_VISITADO;
        return $this;
    }
    
    public function isVisitado() {
        return $this->estado == self::VISITADO;
    }
    
    /**
     * 
     * @return bool
     */
    public function hijosYaGenerados() {
        return $this->hijosYaGenerados;
    }

    /**
     * 
     * @return Nodo
     */
    public function getHijoNoVisitado() {
        foreach ($this->hijos as $hijo) {
            if ($hijo->getEstado() === self::NO_VISITADO) {
                return $hijo;
            }
        }
        return null;
    }

    /**
     * 
     * @param Nodo $nodo
     */
    public function eliminarHijo(Nodo $nodo) {
        foreach ($this->hijos as $key => $hijo) {
            if ($nodo->getProvincia()->getId() === $hijo->getProvincia()->getId()) {
                unset($this->hijos[$key]);
                break;
            }
        }
    }

    public function eliminarHijos() {
        unset($this->hijos);
    }

}
