var progress = " ";
var animationCircle = null;
$(function () {
    loader();
    viewModel = new ProblemaViajanteViewModel();
    inicializarProvincias(viewModel);
    ko.applyBindings(viewModel);
});


function ProblemaViajanteViewModel() {
    self = this;

    self.primeraVez = ko.observable(true);

    self.ajax = ko.observable(false);

    self.provincias = ko.observableArray();

    self.ruta = ko.observableArray();

    self.mejorDistancia = ko.observable();

    self.exhaustivo = function () {
        ajax('exhaustivo');
    };

    self.heuristicoConPartida = function ($provincia) {
        ajax('heuristicoConPartida');
    };

    self.heuristico = function () {
        ajax('heuristico');
    };

    self.genetico = function () {
        var $geneticoForm = $("#geneticoForm")[0];
        if ($geneticoForm.checkValidity()) {
            $("#geneticoModal").modal('hide');
            var parametros = {
                "probCrossover": $('#probCrossover').val(),
                "probMutacion": $('#probMutacion').val(),
                "poblacionSize": $('#poblacionSize').val(),
                "cantCiclos": $('#cantCiclos').val(),
            };
            ajax('genetico', parametros);
        } else {
            $("#submitGenetico").click();
        }
    };

    self.animarMarcador = function (provincia) {
        $marker = provincia.marker;
        $marker.setAnimation(google.maps.Animation.BOUNCE);
    };

    self.desanimarMarcador = function (provincia) {
        $marker = provincia.marker;
        $marker.setAnimation(null);
    }

}


function initMap(center, flightPlanCoordinates) {
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 4,
        center: center,
        mapTypeId: google.maps.MapTypeId.TERRAIN
    });

    var lineSymbol = {
        path: google.maps.SymbolPath.CIRCLE,
        scale: 3,
        strokeColor: '#393'
    };

    var flightPath = new google.maps.Polyline({
        path: flightPlanCoordinates,
        icons: [{
                icon: lineSymbol,
                offset: '100%',
                repeat: true
            }],
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2,
    });

    flightPath.setMap(map);
    animateCircle(flightPath);

    $provincias = viewModel.ruta();
    $.each($provincias, function ($index, $provincia) {
        var marker = new google.maps.Marker({
            position: {lat: $provincia.latitud, lng: $provincia.longitud},
            title: ($index + 1).toString() + '-' + $provincia.nombre,
            animation: google.maps.Animation.DROP,
            label: $provincia.nombre
        });
        marker.setOpacity(0.5);
        marker.setMap(map);
        viewModel.ruta()[$index]["marker"] = marker;
    });

}

function ajax(algoritmo, parametros) {
    var data;
    if (algoritmo === 'genetico') {
        data = {
            "algoritmo": algoritmo,
            "parametros": parametros
        };
    } else {
        data = {"algoritmo": algoritmo};
    }
    viewModel.primeraVez(false);
    var progressFunction;
    var options = {
        url: 'algoritmo.php',
        data: data,
        dataType: "json",
        beforeSend: function (jqXHR, settings) {
            progressFunction = setInterval(function () {
                $.ajax({
                    url: "datos/progreso.txt"
                }).done(function (data) {
                    progress = data;
                });
            }, 1000);
            clear();
            viewModel.ajax(true);
            animateCircle();
        },
        success: function (data, textStatus, jqXHR) {
            self.mejorDistancia(data["mejorDistancia"]);
            self.ruta(data["mejorRuta"]);
            var flightPlanCoordinates = formatForMaps(data);
            var center = {lat: -38.416097, lng: -63.616671999999994};
            initMap(center, flightPlanCoordinates);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        },
        complete: function (jqXHR, textStatus) {
            viewModel.ajax(false);
            clearInterval(progressFunction);
            progress = " ";
        }
    };
    $.ajax(options);
}

function animateCircle(line) {
    var count = 0;
        animationCircle = setInterval(function () {
            count = (count + 1) % 200;

            var icons = line.get('icons');
            icons[0].offset = (count / 2) + '%';
            line.set('icons', icons);
        }, 30);

}

function formatForMaps(data) {
    coordenadasProv = [];
    $provincias = data["mejorRuta"];
    $.each($provincias, function (index, $provincia) {
        coordenadasProv.push(
                {
                    lat: $provincia["latitud"],
                    lng: $provincia["longitud"]
                }
        );
    });
    return coordenadasProv;
}


function inicializarProvincias(viewModel) {
    var provincias;
    var options = {
        url: "provincias.php",
        beforeSend: function (jqXHR, settings) {
        },
        success: function (data, textStatus, jqXHR) {
            viewModel.provincias(JSON.parse(data));
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error al cargar las provincias");
        },
        complete: function (jqXHR, textStatus) {
        }
    };

    $.ajax(options);
    return provincias;
}

function loader() {
    var DrawingThing, SIZE, TWO_PI, c, canvas, createCanvas, ct, drawingThings, quarterSize, threQuarters, trails;

    SIZE = 300;

    quarterSize = SIZE / 4;

    threQuarters = SIZE - quarterSize;

    TWO_PI = Math.PI * 2;

    createCanvas = function () {
        var canvas;
        canvas = document.createElement("canvas");
        canvas.width = SIZE;
        canvas.height = SIZE;
        return canvas;
    };

    canvas = createCanvas();

    var loader = document.getElementById("loader");
    loader.appendChild(canvas);
//    document.body.appendChild(canvas);

    c = canvas.getContext("2d");

    trails = createCanvas();

    ct = trails.getContext("2d");

    var ctx = canvas.getContext("2d");
    var ctxProgress = canvas.getContext("2d");


    clear = function () {
        c.fillStyle = "black";
        c.fillRect(0, 0, SIZE, SIZE);
        ct.fillStyle = "black";
        ct.fillRect(0, 0, SIZE, SIZE);
    };

    clear();

    //    document.getElementById("erase").onclick = clear;

    DrawingThing = (function () {
        function DrawingThing(x, y) {
            this.x = x;
            this.y = y;
            this.radii = [30, 60, 90];
            this.num = this.radii.length;
            this.thetas = [Math.random() * TWO_PI, Math.random() * TWO_PI, Math.random() * TWO_PI];
            this.thetasInc = [Math.random() * 0.2 - 0.1, Math.random() * 0.2 - 0.1, Math.random() * 0.2 - 0.1];
        }

        DrawingThing.prototype.draw = function () {
            ctx.font = "20px arial";
            ctx.fillStyle = "white";
            ctx.textAlign = "center";
            ctx.fillText("CALCULANDO", canvas.width / 2, canvas.height / 6);
            ctxProgress.font = "20px arial";
            ctxProgress.fillStyle = "white";
            ctxProgress.textAlign = "center";
            ctxProgress.fillText(progress.toString(), canvas.width / 2, canvas.height - canvas.height / 8);

            var i, j, ref, x, y;
            ct.strokeStyle = "rgba(255,255,255,0.1)";
            for (i = j = 0, ref = this.num; 0 <= ref ? j < ref : j > ref; i = 0 <= ref ? ++j : --j) {
                x = this.x + this.radii[i] * Math.cos(this.thetas[i]);
                y = this.y + this.radii[i] * Math.sin(this.thetas[i]);
                if (i === 0) {
                    ct.beginPath();
                    ct.moveTo(x, y);
                } else {
                    ct.lineTo(x, y);
                }
                c.strokeStyle = "rgba(255,255,255,0.5)";
                c.fillStyle = "white";
                c.beginPath();
                c.arc(this.x, this.y, this.radii[i], 0, TWO_PI, false);
                c.stroke();
                c.beginPath();
                c.arc(x, y, 2, 0, TWO_PI, false);
                c.fill();
                this.thetas[i] += this.thetasInc[i];
            }
            ct.closePath();
            ct.stroke();
        };

        return DrawingThing;

    })();

    drawingThings = [new DrawingThing(SIZE / 2, SIZE / 2)];

    setInterval(function () {
        var drawThing, j, len, results;
        c.drawImage(trails, 0, 0);
        results = [];
        for (j = 0, len = drawingThings.length; j < len; j++) {
            drawThing = drawingThings[j];
            results.push(drawThing.draw());
        }
        return results;
    }, 30);

    return;
}




