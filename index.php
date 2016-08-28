<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <title>Problema del Viajante</title>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Material Design Bootstrap -->
        <link href="css/mdb.min.css" rel="stylesheet">

        <link href="css/hover.css" rel="stylesheet" media="all">

        <!-- Your custom styles (optional) -->
        <link href="css/style.css" rel="stylesheet">

        <style>
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            #map {
                height: 100%;
            }
        </style>
    </head>
    <body>

        <div id="contenedorLoader" style="display:none" data-bind="visible: ajax()">

            <div id="loader">
            </div>
        </div>
        <div class="container">
            <nav class="navbar navbar-dark navbar-fixed-top bg-primary">

                <!-- Collapse button-->
                <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#collapseEx2"><i class="fa fa-bars"></i></button>

                <div class="container">

                    <!--Collapse content-->
                    <div class="collapse navbar-toggleable-xs" id="collapseEx2">
                        <!--Navbar Brand-->
                        <ul class="nav navbar-brand">
                            <li class="nav-item ">
                                <a id="genetico" role="button" class="nav-link" href="#">Problema del viajante <span class="sr-only">(current)</span></a>
                            </li>
                        </ul>
                        <!--Links-->
                        <ul class="nav navbar-nav pull-right">
                            <li class="nav-item ">
                                <a id="exhaustivo" role="button" class="nav-link" href="#" data-bind="click: exhaustivo">Exhaustivo <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item ">
                                <a id="heuristico" role="button" class="nav-link" href="#" data-bind="click: heuristico">Heuristico <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item ">
                                <div class="btn-group">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Heuristico con partida</button>

                                    <div class="dropdown-menu primary-color" data-bind="foreach: provincias()">
                                        <a class="dropdown-item" data-bind="text: nombre, click: $root.heuristicoConPartida"></a>
                                    </div>
                                </div>
                            </li> 
                            <li class="nav-item ">
                                <a id="genetico" role="button" data-toggle="modal" data-target="#geneticoModal" class="nav-link" href="#">Genetico <span class="sr-only">(current)</span></a>
                            </li>
                        </ul>
                    </div>
                    <!--/.Collapse content-->
                </div>

            </nav>
            <!--ko if: primeraVez()-->
            <div class="jumbotron">
                <div class="well">
                    <h1>Problema del viajante</h1>
                    Elije el tipo de algoritmo en la parte superior derecha 
                </div>
            </div>
            <!--/ko-->
        </div>

        <div id="map" style="display: none" data-bind="visible: !primeraVez()">
        </div>


        <div id="ruta" class="container" style="display:none" data-bind="visible: ruta().length > 0">
            <div class="panel panel-primary">
                <div class="panel-heading bg-primary">
                    <span style="padding-left: 5px" data-bind="text: 'Ruta: ' + $root.mejorDistancia() + ' Kms'"></span>
                </div>
                <div class="panel-body scrollable">
                    <ul class="list-group" data-bind="foreach: ruta()">
                        <li style="display: block" class="list-group-item hvr-rectangle-out" data-bind="text: ($index()+1).toString() +'-'+nombre, event: {mouseover: $root.animarMarcador,mouseout:$root.desanimarMarcador}"></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="modal fade" id="geneticoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <!--Content-->
                <div class="modal-content">
                    <!--Header-->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Parámetros algoritmo genetico</h4>
                    </div>
                    <!--Body-->
                    <div class="modal-body">
                        <form id="geneticoForm">
                            <div class="md-form">
                                <input type="number" min="0" max="1" step="0.01" value="0.75" id="probCrossover" class="form-control">
                                <label for="probCrossover" class="">Probabilidad crossover</label>
                            </div>
                            <div class="md-form">
                                <input type="number" min="0" max="1" step="0.01" value="0.05" id="probMutacion" class="form-control">
                                <label for="probMutacion" class="">Probabilidad mutación</label>
                            </div>
                            <div class="md-form">
                                <input type="number" min="1" max="500" step="1" value="50" id="poblacionSize" class="form-control">
                                <label for="poblacionSize" class="">Tamaño de población</label>
                            </div>
                            <div class="md-form">
                                <input type="number" min="10" max="500" step="5" value="20" id="cantCiclos" class="form-control">
                                <label for="cantCiclos" class="">Cantidad de ciclos</label>
                            </div>
                            <input id="submitGenetico" type="submit" style="display:none">
                        </form>
                    </div>
                    <!--Footer-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" data-bind="click: genetico">Ejecutar</button>
                    </div>
                </div>
                <!--/.Content-->
            </div>
        </div>

        <!-- JQuery -->
        <script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>

        <!-- Bootstrap tooltips -->
        <script type="text/javascript" src="js/tether.min.js"></script>

        <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="js/bootstrap.min.js"></script>

        <!-- MDB core JavaScript -->
        <script type="text/javascript" src="js/mdb.min.js"></script>

        <script type="text/javascript" src="js/ko.js"></script>

        <script src="js/scripts.js"></script>

        <script 
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCWvnCGU7K-CU6N-goGu-91NEc_6B7MULA&signed_in=true"></script>
    </body>
</html>
