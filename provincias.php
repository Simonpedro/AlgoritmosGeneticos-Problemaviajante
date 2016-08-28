<?php 
include "RepositorioProvincias.php";

$repo = new RepositorioProvincias();
$provincias = $repo->getProvincias();

$provincias = json_encode($provincias);
echo $provincias;
?>

