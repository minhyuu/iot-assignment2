<!DOCTYPE HTML>
<html>
    <head>  
        <meta charset="UTF-8">
        <script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
        <script>
            window.onload = function () {

                var lightData = [];
                var levelData = [];
                var timestamp = [];
                
                //Start creating a Chart object
                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    title: {
                        text: "STREET LIGHT DATA CHART"
                    },
                    
                    axisX: {
                        title: "TimeStamp"
                        valueFormatString: "DD-MM-YY HH:mm:ss"
                    },
                    
                    axisY: {
                        title: "Level",
                        prefix: "",
                        suffix: " C",

                        tickLength: 5,
                        tickColor: "DarkSlateBlue" ,
                        tickThickness: 1
                    },

                    toolTip: {
                        shared: true
                    },
                    
                    legend: {
                        cursor: "pointer",
                        verticalAlign: "top",
                        horizontalAlign: "center",
                        dockInsidePlotArea: true,
                        itemclick: toogleDataSeries
                    },

                    data: [
                        {
                            type:"line",
                            axisYType: "secondary",
                            name: "Light",
                            showInLegend: true,
                            markerSize: 0,
                            yValueFormatString: "#",
                            xValueType: "dateTime",
                            color: "blue",
                            dataPoints: lightData
                        },
                        {
                        
                            type:"line",
                            axisYType: "secondary",
                            name: "Power",
                            showInLegend: true,
                            markerSize: 0,
                            yValueFormatString: "#",
                            xValueType: "dateTime",
                            color: "green",
                            dataPoints: powerData
                        },
                    ]
                });

                chart.render(); // Actually put the chart on the web page

                function toogleDataSeries(e){

                    if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                        e.dataSeries.visible = false;
                    } else{
                        e.dataSeries.visible = true;
                    }

                    chart.render();
                }


                function addData(data) {	
                    
                        //var yawData = [];
                        //var pitchData = [];
                        //var rollData = [];
                        
                        console.log("add data");

                        for (var i = 0; i < data.record.length; i++) {

                            currentValues = data.record[i];				
                            // Convert date string to date object for CanvasJS
                            [dateValues, timeValues] = currentValues.date.toString().split(' ');
                            [month, day, year] = dateValues.split('-');
                            [hours, minutes, seconds] = timeValues.split(':');
                            date_in = new Date(+year, +month - 1, +day, +hours, +minutes, +seconds);
                            
                            // Get orientation data
                            light = currentValues.streetlight.light_level;
                            power = currentValues.streetlight.power_level;

                            lightData.push( {x: date_in, y: (light * 1.0)});
                            powerData.push( {x: date_in, y: (power * 1.0)});
                        }
                        
                        chart.options.data[0].dataPoints = lightData;
                        chart.options.data[1].dataPoints = powerData;
                        
                        chart.render();			
                        console.log(tempData);		
                        setTimeout(updateData, 2000);
                }

                function updateData() {
                    $.getJSON("http://iotserver.com/convertXMLStreetlightToJSON.php", addData);				
                }
                
                setTimeout(updateData, 1000);
            }
        </script>
    </head>

    <body>
        <div id="chartContainer" style="height: 370px; max-width: 1520px; margin: 0px auto;"></div>
        <script src="../../canvasjs.min.js"></script>
    </body>
</html>
