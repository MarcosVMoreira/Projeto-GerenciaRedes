<!DOCTYPE HTML>
<html>
<head>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" 
integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" 
integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script>

        var bool = [];
        var bool1 = [];
        var anterior = 0;

        var ipAlvo = $('#ipAlvo').val();

        function iniciaGrafico () {

            ipAlvo = $('#ipAlvo').val();

            $.post('consulta.php', {
                ipAlvo: ipAlvo
            }, function (retorno) {
                let jsonDados = JSON.parse(retorno);
                anterior = jsonDados['y'];
                anterior1 = jsonDados['y1'];
            });

            var chart = new CanvasJS.Chart("chartContainer", {
                theme: "light2",
                title: {
                    text: "Uso de rede"
                },
                axisX:{
                    title: "Tempo"
                },
                axisY:{
                    logarithmic: true,
                    includeZero: false,
                    suffix: " Bits"
                },
                data: [{
                    type: "line",
                    yValueFormatString: "#,##0.0#",
                    toolTipContent: "{y} Bits",
                    dataPoints: bool
                },
                {        
                    type: "line",
                    yValueFormatString: "#,##0.0#",
                    toolTipContent: "{y} Bits",
                    dataPoints: bool1
                }
                
                ]
            });

            chart.render();
            
            var updateInterval = 3000;
            setInterval(function () { updateChart(chart) }, updateInterval);
            
            var xValue;
            var yValue;

        }

        function updateChart(chart) {

            $.post('consulta.php', {
                ipAlvo: ipAlvo
            }, function (retorno) {
                //yValue = parseFloat(retorno);
                let jsonDados = JSON.parse(retorno);
                xValue = jsonDados.label;
                yValue = jsonDados['y'];

                var aux, aux2;

                aux = jsonDados['y'];
                aux1 = jsonDados['y1'];

                jsonDados['y'] = jsonDados['y']-anterior;
                jsonDados['y1'] = jsonDados['y1']-anterior1;

                anterior = aux;
                anterior1 = aux1;

                var vetorAuxiliar = {label:jsonDados.label, y:jsonDados['y1']};

                let jsonString = JSON.stringify(vetorAuxiliar);

                let jsonDados1 = JSON.parse(jsonString);

                bool.push( jsonDados );

                bool1.push( jsonDados1 );
                
                chart.render();

            });
        };
        
    </script>

</head>

<body>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="style.css">


    <!-- colocar aqui os inputs -->

    <div class="container-fluid">
        <div class="col-12 offset-4">
            <div class="col-4">
                <div class="input-group pt-5 pb-5">
                    <input type="text" class="form-control" name="ipAlvo" id="ipAlvo" placeholder="Insira o IP alvo" required autofocus/>
                    <button type="button" id="btnEnviar" class="btn btn-success" onclick="iniciaGrafico();">Enviar IP</button>
                </div>
            </div>
        </div>
    </div>


    <div id="chartContainer" style="height: 370px; width: 100%;"></div>

    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>


</body>

</html>     
