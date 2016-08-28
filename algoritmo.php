<?php

set_time_limit(10000);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (!is_null($_REQUEST['checkTime'])) {
    $time_start = microtime(true);
}

include_once "AlgoritmoBase.php";
include_once "Exhaustivo.php";
include_once "HeuristicoConPartida.php";
include_once "Heuristico.php";
include_once "Genetico.php";

$algoritmo = $_REQUEST["algoritmo"];

if ($algoritmo === "exhaustivo") {
    $result = (new Exhaustivo())->correr();
    $result = json_encode($result);
    echo $result;
} else if ($algoritmo === "heuristicoConPartida") {
    $idProvincia = $algoritmo = $_REQUEST["id"];
    $result = (new HeuristicoConPartida($idProvincia))->correr();
    $result = json_encode($result);
    echo $result;
} else if ($algoritmo === "heuristico") {
    $result = (new Heuristico())->correr();
    $result = json_encode($result);
    echo $result;
} else if ($algoritmo === "genetico") {
    $parametros = $_REQUEST["parametros"];
    if (is_null($parametros)) {
        $result = (new Genetico(0.75, 0.05))->correr();
    } else {
        $probCrossover = floatval($parametros["probCrossover"]);
        $probMutacion = floatval($parametros["probMutacion"]);
        $poblacionSize = intval($parametros["poblacionSize"]);
        $poblacionSize = ($poblacionSize % 2 == 0) ? $poblacionSize + 1 : $poblacionSize;
        $cantCiclos = intval($parametros["cantCiclos"]);
        $result = (new Genetico($probCrossover, $probMutacion, $poblacionSize, $cantCiclos))->correr();
    }
    $result = json_encode($result);
    echo $result;
}
if (!is_null($_REQUEST['checkTime'])) {
    $time_end = microtime(true);
    $execution_time = ($time_end - $time_start) / 60;
    echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
}



