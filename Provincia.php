<?php
/**
 * Description of Provincia
 *
 * @author Pedro
 */
class Provincia implements JsonSerializable{
    
    protected static $contId = 0;
    
    protected $id;
    
    protected $nombre;
    
    protected $latitud;
    
    protected $longitud;
    
    public function __construct($nombre, $latitud, $longitud) {
        $this->id = Provincia::$contId++;
        $this->nombre = $nombre;
        $this->latitud = $latitud;
        $this->longitud = $longitud;
    }
    
    public function __toString() {
        return strval($this->getId());
    }
    
    // <editor-fold defaultstate="collapsed" desc="Getters">
    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getLatitud() {
        return $this->latitud;
    }

    function getLongitud() {
        return $this->longitud;
    }

    // </editor-fold>


    // <editor-fold defaultstate="collapsed" desc="Setters">
    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setLatitud($latitud) {
        $this->latitud = $latitud;
    }

    function setLongitud($longitud) {
        $this->longitud = $longitud;
    }
    // </editor-fold>

    public function jsonSerialize() {
        return [
           'id' => $this->id,
           'nombre' => $this->nombre,
           'latitud' => floatval($this->latitud),
           'longitud' => floatval($this->longitud)
        ];
    }

}
