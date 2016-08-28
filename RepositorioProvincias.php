<?php
include_once "Provincia.php";
/**
 * Description of RepositorioProvincias
 *
 * @author Pedro
 */
class RepositorioProvincias {
    
    protected $provincias;

    public function __construct() {
        $this->provincias = [];
        $this->init();
    }

    public function init() {
        $handle = fopen("datos/provincias.csv", "r");
        if ($handle) {
            $line = fgetss($handle);
            while ($line !== false) {
                $parts = split(";", $line);
                $this->provincias[] = new Provincia($parts[0], $parts[1], $parts[2]);
                $line = fgetss($handle);
            }
        } else {
            echo "no se pudo abrir el archivo";
        }
        fclose($handle);
    }
    
    /*
     * @var []|Provincia
     */
    function getProvincias() {
        return $this->provincias;
    }

    /**
     * 
     * @param int $id
     * @return Provincia
     */
    public function getProvincia($id) {
        foreach ($this->provincias as $pro) {
            if ($pro->getId() === $id) {
                return $pro;
            }
        }
        return null;
    }
}
