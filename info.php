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
        var udpInfo = [];
        var udpInfo1 = [];
        var udpInfoIn = [];
        var udpInfo1In = [];
        var anterior = 0;
        var anterior1 = 0;

        var ipAlvo = $('#ipAlvo').val();

        $( document ).ready(function() {
/*

            $.post('consulta.php', {
                ipAlvo: ipAlvo,
                tipoConsulta: "maquinaInfo"
            }, function (retorno) {
                let jsonDados = JSON.parse(retorno);

                $("#processador").append(jsonDados['1']);
                $("#sistemaOperacional").append(jsonDados['2']);
                $("#totalRAM").append(jsonDados['3']);
                $("#tempoLigado").append(jsonDados['4']);

            });*/

        });

        function iniciaGrafico () {

            ipAlvo = $('#ipAlvo').val();

            $.post('consulta.php', {
                ipAlvo: ipAlvo,
                tipoConsulta: "maquinaInfo"
            }, function (retorno) {
                let jsonDados = JSON.parse(retorno);

                $("#tempoLigado").append(jsonDados['1']);
                $("#totalRAM").append(jsonDados['2']+" Kb ou "+jsonDados['2']/1000000+" GB");
                $("#processador").append(jsonDados['3']);
                $("#sistemaOperacional").append(jsonDados['4']);

            });

            $.post('consulta.php', {
                ipAlvo: ipAlvo,
                tipoConsulta: "banda"
            }, function (retorno) {
                let jsonDados = JSON.parse(retorno);
                anterior = jsonDados['y'];
                anterior1 = jsonDados['y1'];
            });

            $.post('consulta.php', {
                ipAlvo: ipAlvo,
                tipoConsulta: "tcpudp"
            }, function (retorno) {
                let jsonDados = JSON.parse(retorno);
                anteriorUdp = jsonDados['y'];
                anterior1Udp = jsonDados['y1'];
            });

            $.post('consulta.php', {
                ipAlvo: ipAlvo,
                tipoConsulta: "tcpudpIn"
            }, function (retorno) {
                let jsonDados = JSON.parse(retorno);
                anteriorUdpIn = jsonDados['y'];
                anterior1UdpIn = jsonDados['y1'];
            });

            var chart = new CanvasJS.Chart("bandwidthChart", {
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
                    showInLegend: true, 
                    legendText: "Saída",
                    yValueFormatString: "#,##0.0#",
                    toolTipContent: "{y} Bits",
                    dataPoints: bool
                },
                {        
                    type: "line",
                    showInLegend: true, 
                    legendText: "Entrada",
                    yValueFormatString: "#,##0.0#",
                    toolTipContent: "{y} Bits",
                    dataPoints: bool1
                }
                
                ]
            });

            chart.render();

            var chart1 = new CanvasJS.Chart("tcpUdpChart", {
                theme: "light2",
                title: {
                    text: "Pacotes TCP/UDP enviados"
                },
                axisX:{
                    title: "Tempo"
                },
                axisY:{
                    //logarithmic: true,
                    includeZero: false,
                    suffix: " Número de pacotes"
                },
                data: [{
                    type: "line",
                    showInLegend: true, 

                    legendText: "TCP",
                    toolTipContent: "{y} Pacotes",
                    dataPoints: udpInfo
                },
                {        
                    type: "line",
                    showInLegend: true, 

                    legendText: "UDP",
                    toolTipContent: "{y} Pacotes",
                    dataPoints: udpInfo1
                }
                
                ]
            });

            chart1.render();

            var chart2 = new CanvasJS.Chart("tcpUdpInChart", {
                theme: "light2",
                title: {
                    text: "Pacotes TCP/UDP recebidos"
                },
                axisX:{
                    title: "Tempo"
                },
                axisY:{
                    //logarithmic: true,
                    includeZero: false,
                    suffix: " Número de pacotes"
                },
                data: [{
                    type: "line",
                    showInLegend: true, 

                    legendText: "TCP",
                    toolTipContent: "{y} Pacotes",
                    dataPoints: udpInfoIn
                },
                {        
                    type: "line",
                    showInLegend: true, 

                    legendText: "UDP",
                    toolTipContent: "{y} Pacotes",
                    dataPoints: udpInfo1In
                }
                
                ]
            });

            chart2.render();
            
            var updateInterval = 3000;
            setInterval(function () { updateChart(chart, chart1, chart2) }, updateInterval);
            
            var xValue;
            var yValue;

        }

        function updateChart(chart, chart1, chart2) {

            processaBandwidth(chart);
            processaUdpTcp(chart1);
            processaUdpTcpIn(chart2);


        };

        function processaBandwidth (chart) {
            $.post('consulta.php', {
                ipAlvo: ipAlvo,
                tipoConsulta: "banda"
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

        function processaUdpTcp (chart) {
            $.post('consulta.php', {
                ipAlvo: ipAlvo,
                tipoConsulta: "tcpudp"
            }, function (retorno) {
                //yValue = parseFloat(retorno);
                let jsonDados = JSON.parse(retorno);

                xValueUdp = jsonDados.label;
                yValueUdp = jsonDados['y'];

                var auxUdp, aux1Udp;

                auxUdp = jsonDados['y'];
                aux1Udp = jsonDados['y1'];

                jsonDados['y'] = jsonDados['y']-anteriorUdp;
                jsonDados['y1'] = jsonDados['y1']-anterior1Udp;

                anteriorUdp = auxUdp;
                anterior1Udp = aux1Udp;

                var vetorAuxiliarUdp = {label:jsonDados.label, y:jsonDados['y1']};

                let jsonString = JSON.stringify(vetorAuxiliarUdp);

                let jsonDados1 = JSON.parse(jsonString);

                udpInfo.push( jsonDados );
                udpInfo1.push( jsonDados1 );

                //172.16.103.144
                chart.render();

            });
        };
        
        function processaUdpTcpIn (chart) {
            $.post('consulta.php', {
                ipAlvo: ipAlvo,
                tipoConsulta: "tcpudpIn"
            }, function (retorno) {
                //yValue = parseFloat(retorno);
                let jsonDados = JSON.parse(retorno);

                xValueUdpIn = jsonDados.label;
                yValueUdpIn = jsonDados['y'];

                var auxUdpIn, aux1UdpIn;

                auxUdpIn = jsonDados['y'];
                aux1UdpIn = jsonDados['y1'];

                jsonDados['y'] = jsonDados['y']-anteriorUdpIn;
                jsonDados['y1'] = jsonDados['y1']-anterior1UdpIn;

                anteriorUdpIn = auxUdpIn;
                anterior1UdpIn = aux1UdpIn;

                var vetorAuxiliarUdpIn = {label:jsonDados.label, y:jsonDados['y1']};

                let jsonString = JSON.stringify(vetorAuxiliarUdpIn);

                let jsonDados1 = JSON.parse(jsonString);

                udpInfoIn.push( jsonDados );
                udpInfo1In.push( jsonDados1 );

                //172.16.103.144
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
        <div class="col-8 offset-4">
            <div class="col-6">
                <div class="input-group pt-5 pb-5">
                    <input type="text" class="form-control" name="ipAlvo" id="ipAlvo" placeholder="Insira o IP alvo" required autofocus/>
                    <button type="button" id="btnEnviar" class="btn btn-success" onclick="iniciaGrafico();">Enviar IP</button>
                </div>
            </div>
        </div>
        <div class="col-8 offset-4">
            <div class="row">
                Processador:
                <div class="col-12" id="processador">
                </div>
            </div>
            <div class="row">
                Sistema operacional:
                <div class="col-12" id="sistemaOperacional">
                </div>
            </div>
            <div class="row">
                Total de RAM:
                <div class="col-12" id="totalRAM">
                </div>
            </div>
            <div class="row">
                Tempo ligado:
                <div class="col-12" id="tempoLigado">
                </div>
            </div>
        </div>
    </div>

    <div id="bandwidthChart" style="height: 300px; width: 50%;"></div>
    <div id="tcpUdpChart" style="height: 300px; width: 50%;"></div>
    <div id="tcpUdpInChart" style="height: 300px; width: 50%;"></div>

    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>


</body>

</html>     
