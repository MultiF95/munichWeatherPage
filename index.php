<?php
header('Content-Type: text/html; charset=utf-8');
// wetteronline.de values
$basepath = "https://api.wetteronline.de/weather?";
$package = "hourly72"; // individuelle UserID
$uid = "Y29uc29s"; // "Secret" des Accounts zur UserID
$secret = "c3RxYnRzZ2g0bmxxZXZ2";
$gid = "10866";
// $gid = "10518,10513,10400";
if (isset($basepath) == false || isset($package) == false || isset($uid) == false || isset($secret) == false || isset($gid) == false)
{echo "at least one of the required parameters is missing"; exit();}
$date = new DateTime("now", new DateTimeZone('UTC')); // query-parameter zusammenstellen
$data = array(
'date' => $date->format('Y-m-d-H'), 'package' => $package, 'uid' => $uid, 'gid' => $gid,);
ksort($data);
$data["checksum"] = base64_encode(md5(implode("|", $data) . "|" . $secret, true)); // query string bilden
$qs = http_build_query($data, null, '&', PHP_QUERY_RFC3986); // pfad für API-request zusammenbauen
$apipath = $basepath . $qs;
$result = @file_get_contents($apipath);
if ($result == false) exit("Error: " . $http_response_header[0]); // Ergebnis in ein JSON-Objekt umwandeln
$json = json_decode($result);
//echo "<a href=\"$apipath\" target=\"_blank\">(source)</a><br><br>\r\n";
//echo "<pre>"; print_r($json); echo "</pre>";
$location = $json->info->locations[0];
/* for ($o = 0; $o <= 23; $o++){
	$celo = $json->$location->data[0]->periods[$o]->tt_C;
        //$popo = $json->$location->data[0]->periods[$o]->pop;
        $houro = $json->$location->data[0]->periods[$o]->periodname;
        echo "".$houro." = ".$celo."% <br>"; } 
*/
//echo "used HotD.: ".$ut."<br>";
//echo $json->info->locations[0];

//##### weather.com values
$jsonw = shell_exec('curl --cookie "USER_TOKEN=Yes" -s https://www.wetter.de/deutschland/wetter-muenchen-18225562/wetterbericht-aktuell.html | sed -n \'/var weather_/,/<\/script>/p\' | tail -n +2 | head -n -1 | cut -c 49- |sed \'s/.$//\'');
$jsonwt = shell_exec('curl --cookie "USER_TOKEN=Yes" -s https://www.wetter.de/deutschland/wetter-muenchen-18225562/wetterbericht-morgen.html | sed -n \'/var weather_/,/<\/script>/p\' | tail -n +2 | head -n -1 | cut -c 49- |sed \'s/.$//\'');
$jsonwtt = shell_exec('curl --cookie "USER_TOKEN=Yes" -s https://www.wetter.de/deutschland/wetter-muenchen-18225562/wetterbericht-uebermorgen.html | sed -n \'/var weather_/,/<\/script>/p\' | tail -n +2 | head -n -1 | cut -c 49- |sed \'s/.$//\'');
$d = json_decode($jsonw);
$dt = json_decode($jsonwt);
$dtt = json_decode($jsonwtt);
//echo $jsonwt;
//####### weather.com
//$jwc = shell_exec('curl --cookie "USER_TOKEN=Yes" -s https://weather.com/de-DE/wetter/stundlich/l/GMXX1002 | grep -o \'"HourlyForecast":[^<>]*"FifteenMinute":\' | cut -c 18-');
//$jwc = shell_exec('curl -s https://api.weather.com/v2/turbo/vt1hourlyhorecast?apiKey=d522aa97197fd864d36b418f39ebb323&format=json&geocode=48.14%2C11.58&language=de-DE&units=m');
$url = "https://api.weather.com/v2/turbo/vt1hourlyforecast?apiKey=d522aa97197fd864d36b418f39ebb323&format=json&geocode=48.14%2C11.58&language=de-DE&units=m";
$jwc = file_get_contents($url);
//$jwc = substr($jwc, 0, -18);
$jwc = json_decode($jwc);
//$jwc = json_decode($jwc);
//var_dump(json_decode($result, true));

//$geocode = "geocode:48.14,11.58:language:de-DE:units:m";
//echo $jwc;
//echo $jwc->vt1hourlyforecast->precipPct[0];

//############## OPM VALUES
//$jsono = shell_exec('curl --cookie "USER_TOKEN=Yes" -s "http://api.openweathermap.org/data/2.5/forecast?q=Munich&appid=94bf8b622daadbfc0cb89d950907d86b"');
//$jo = json_decode($jsono)
//echo $jo->;
//for ($i=0; $i < 24; $i++){
//		echo " ".$jo->list[$i]->clouds->all." ";
//}
// 00 03 06 09 12 15 18 21 
// 1  2  3  4  5  6  7  8  
echo "<br><br>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<link rel='icon' href='https://favicon-generator.org/favicon-generator/htdocs/favicons/2014-12-30/d63c381d94ed1ba4db0582b755396159.ico' type='image/x-icon'/ >
<style>
body {
        width:1000px;
        margin-left:auto;
	margin-right:auto;
	color: white;
}
</style>
	<title>Weather Graphs</title>
    <head>
	
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>amCharts examples</title>
        <link rel="stylesheet" href="style.css" type="text/css">
        <script src="../amcharts/amcharts.js" type="text/javascript"></script>
        <script src="../amcharts/serial.js" type="text/javascript"></script>
        <script src="https://www.amcharts.com/lib/3/themes/dark.js"></script>
	<script>
        	var chart;
		var chartDataToday = [ 
		<?php 
		$c=-2;
		$p=0;
		$l=0;
		for ($i = 0; $i <= 23; $i++){ // <= 23
			$pop = $json->$location->data[0]->periods[$i]->pop;
			$cel = $json->$location->data[0]->periods[$i]->tt_C;
			$hour = $json->$location->data[0]->periods[$i]->periodname;
			//$hour = date('H');
			$rr_mm = $json->$location->data[0]->periods[$i]->rr_mm*10;
			$temp = $json->$location->data[0]->periods[$i]->tt_C;
			$now = date('H')+":00";
			$noww = date('H');
			$c++;
			echo "{ \"country\": \" ";
			if($i == $noww){ echo "•"; }else{ echo $i; }
			//if($hour == $now){ echo date('H:i'); }else{ echo $hour; }
			echo " \", visits: \"".$pop."\", ";
			$a3=$pop;
			echo "rainWDe: \"";
			if ($d->detail[$i]->rain_chance_3h){
			echo $d->detail[$i]->rain_chance_3h;
			$a1=$d->detail[$i]->rain_chance_3h;
			}
			if (empty($d->detail[$i]->rain_chance_3h)){
			echo $d->detail[$i-1]->rain_chance_3h;
			$a1=$d->detail[$i-1]->rain_chance_3h;
			}
			if (empty($d->detail[$i-1]->rain_chance_3h)){
			echo $d->detail[$i-2]->rain_chance_3h;
			$a1=$d->detail[$i-2]->rain_chance_3h;
			}
			if ($i == 0){ echo $d->detail[2]->rain_chance_3h; $a1=$d->detail[2]->rain_chance_3h;}
			if ($i == 1){ echo $d->detail[2]->rain_chance_3h; $a1=$d->detail[2]->rain_chance_3h;}
			echo "\",";
			echo "rr_mm: \"";
			echo $rr_mm;
			echo "\",";
			echo "temp: \"";
			echo $temp;
			echo "\",";
			echo "rainWC: \"";
			if ($c >= $noww ){
				$p++;
				echo $jwc->vt1hourlyforecast->precipPct[$p];
				$a2=$jwc->vt1hourlyforecast->precipPct[$p];
			}else{
				if ($hour == $now){}else{}
			}
			if($hour == $now){ 
				echo $jwc->vt1hourlyforecast->precipPct[0]; 
				$a2=$jwc->vt1hourlyforecast->precipPct[0]; }
			echo "\",";
			echo "avg: \"";
			$aa = $a1+$a2+$a3;
			$avg = $aa/3;
			if(date('H') <= $i){ 
				echo round($avg);
			}
			
			echo "\"},";	
			
		}  ?>  ];
	
		var chartDataTomorrow = [ 
        	<?php 
		for ($i = 0; $i <= 23; $i++){
			$p++;
			$pop = $json->$location->data[1]->periods[$i]->pop;
                	$hour = $json->$location->data[1]->periods[$i]->periodname;
			$rr_mm = $json->$location->data[1]->periods[$i]->rr_mm*10;
			$temp = $json->$location->data[1]->periods[$i]->tt_C;
                	echo "{ \"country\": \" ".$i." \", visits: \"".$pop."\", ";
			$a3=$pop;
			echo "rainWDe: \"";
			//echo $dt->detail[$i]->rain_amount*10;
			if ($dt->detail[$i]->rain_chance_3h){
                        echo $dt->detail[$i]->rain_chance_3h;
                        $a2=$dt->detail[$i]->rain_chance_3h;
			}
                        if (empty($dt->detail[$i]->rain_chance_3h)){
                        echo $dt->detail[$i-1]->rain_chance_3h;
                        $a2=$dt->detail[$i-1]->rain_chance_3h;
			}
                        if (empty($dt->detail[$i-1]->rain_chance_3h)){
                        echo $dt->detail[$i-2]->rain_chance_3h;
			$a2=$dt->detail[$i-2]->rain_chance_3h;
                        }
                        if ($i == 0){ echo $dt->detail[2]->rain_chance_3h;
				$a2=$dt->detail[2]->rain_chance_3h;}
                        if ($i == 1){ echo $dt->detail[2]->rain_chance_3h;
				$a2=$dt->detail[2]->rain_chance_3h;}
			echo "\",";
			echo "cel: \"";
			echo $cel;
			echo "\",";
			echo "rr_mm: \"";
			echo $rr_mm;
			echo "\",";
			echo "temp: \"";
			echo $temp;
			echo "\",";
			echo "rainWC: \"";
			echo $jwc->vt1hourlyforecast->precipPct[$p];
                        $a1=$jwc->vt1hourlyforecast->precipPct[$p];
			echo "\",";
                        echo "avg: \"";
                        $aa = $a1+$a2+$a3;
                        $avg = $aa/3;
                        echo round($avg);
			echo "\"},";
		}  ?>  ];

		var chartDataTT = [ 
		<?php
		for ($i = 0; $i <= 23; $i++){
			$p++;$q=3;
			$pop = $json->$location->data[2]->periods[$i]->pop;
                	$hour = $json->$location->data[2]->periods[$i]->periodname;
                	$rr_mm = $json->$location->data[2]->periods[$i]->rr_mm*10;
			$temp = $json->$location->data[2]->periods[$i]->tt_C;
			echo "{ \"country\": \" ".$i." \", visits: \"".$pop."\", ";
			$a3=$pop;
			echo "rainWDe: \"";
			//echo $dtt->detail[$i]->rain_amount*10;
			if ($dtt->detail[$i]->rain_chance_3h){
                        echo $dtt->detail[$i]->rain_chance_3h;
			$a2=$dtt->detail[$i]->rain_chance_3h;
                        }
                        if (empty($dtt->detail[$i]->rain_chance_3h)){
                        echo $dtt->detail[$i-1]->rain_chance_3h;
			$a2=$dtt->detail[$i-1]->rain_chance_3h;
                        }
                        if (empty($dtt->detail[$i-1]->rain_chance_3h)){
                        echo $dtt->detail[$i-2]->rain_chance_3h;
			$a2=$dtt->detail[$i-2]->rain_chance_3h;
                        }
                        if ($i == 0){ echo $dtt->detail[2]->rain_chance_3h;
			$a2=$dtt->detail[2]->rain_chance_3h;
			}
                        if ($i == 1){ echo $dtt->detail[2]->rain_chance_3h;
			$a2=$dtt->detail[2]->rain_chance_3h;
			}
			echo "\",";
			echo "rr_mm: \"";
                	echo $rr_mm;
			echo "\",";
			echo "temp: \"";
			echo $temp;
			echo "\",";
			echo "rainWC: \"";
			echo $jwc->vt1hourlyforecast->precipPct[$p];
			if (date('H')<=$i){
				$q=2;
				$a1=0;
			}
			echo "\", ";
			echo "avg: \"";
			$aa = $a1+$a2+$a3;
			$avg = $aa/$q;
			echo round($avg);
			echo "\"},";
                }  ?>  ];
		
		AmCharts.theme = AmCharts.themes.dark;
            AmCharts.ready(function () {
                //chart = new AmCharts.AmSerialChart(AmCharts.themes.dark);
                chart = new AmCharts.AmSerialChart(AmCharts.themes.dark);
                chart.dataProvider = chartDataToday;
                chart.categoryField = "country"
                chart.startDuration = 0;
                var categoryAxis = chart.categoryAxis;
		categoryAxis.gridPosition = "start"

		var graph = new AmCharts.AmGraph();
                graph.valueField = "visits";
                graph.balloonText = "<img src='https://www.google.com/s2/favicons?domain=https://www.wetteronline.de/'>[[category]]Uhr: <b>[[value]]%</b>";
                //graph.balloonText = "";
                graph.type = "smoothedLine";
                graph.lineAlpha = 1;
                graph.lineThickness = 1;
                graph.fillAlphas = 0.2;
                graph.lineColor = "#6684fb";
                //graph.fillColor = "#6684fb";
                //graph.fillColors = ["#2A2A2A", "#6684fb"];
                graph.fillColors = ["#6684fb", "#2A2A2A"];
                
                var graphW = new AmCharts.AmGraph();
                graphW.valueField = "rainWC";
                graphW.type = "smoothedLine";
                graphW.balloonText = "<img src='https://www.google.com/s2/favicons?domain=https://www.wunderground.com/'></>[[category]]Uhr: <b>[[value]]%</b>";
                //graphW.balloonText = "";
                graphW.lineThickness = 1;
                graphW.fillAlphas = 0.2;
                graphW.lineColor ="#FFD30D";
                //graphW.fillColor ="#FFD30D";
                graphW.fillColors = ["#FFD30D", "#2A2A2A"];
		
		var graphC = new AmCharts.AmGraph();
		graphC.valueField = "avg";
                graphC.balloonText = "<img src='https://www.google.com/s2/favicons?domain=https://docs.amcharts.com/'></>[[category]]Uhr: <b>[[value]]%</b>";
		//graphC.showBalloonAt: "open";
		graphC.type = "step";
		//graphC.lineAlpha = 1;
		graphC.lineThickness = 3;
		graphC.fillAlphas = 0.2;
		graphC.lineColor = "#ffffff";
                graphC.fillColors = ["#FFFFFF", "#2A2A2A"];

		var graphT = new AmCharts.AmGraph();
                graphT.valueField = "temp";
                graphT.balloonText = "<b>[[value]] °C</b>";
                //graphC.showBalloonAt: "open";
                graphT.type = "smoothedLine";
                graphT.lineAlpha = 1;
                graphT.lineThickness = 2;
                graphT.fillAlphas = 0;
                graphT.lineColor = "#E6E600";
		
		var graphX = new AmCharts.AmGraph();
                graphX.valueField = "rainWDe";
                graphX.balloonText = "";
                //graphX.showBalloonAt: "h";
                graphX.balloonText = "<img src='https://www.google.com/s2/favicons?domain=https://www.wetter.de/'></>[[category]]Uhr: <b>[[value]]%</b>";
                //graphX.balloonText = "";
                graphX.type = "smoothedLine";
                graphX.lineAlpha = 1;
                graphX.lineThickness = 1;
                graphX.fillAlphas = 0.2;
                graphX.lineColor = "#24d422";		
		graphX.fillColors = ["#24d442", "#2A2A2A"];
		graphX.showOnAxis= true;

		graphX.hidden = true;
		chart.addGraph(graph);
		chart.addGraph(graphX);
		//chart.addGraph(graphT);
		chart.addGraph(graphW);
		chart.addGraph(graphC);
		
		var yAxis = new AmCharts.ValueAxis();
                yAxis.position = "left";
                chart.addValueAxis(yAxis);
                yAxis.maximum = 95;
                yAxis.minimum = 0;

		var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorAlpha = 0;
                chartCursor.zoomable = false;
                chartCursor.categoryBalloonEnabled = false;
                chart.addChartCursor(chartCursor);
                chart.creditsPosition = "top-right";

		chart.balloon.borderThickness = 1;
		chart.balloon.color = "#DDDDDD";
		chart.balloon.borderColor = "000000";
		chart.balloon.fillColor = "000000";
		chart.balloon.fillAlpha = 0.8;
		
		chart.addClassNames = true;
		var legend = new AmCharts.AmLegend();
		chart.addLegend(legend);
		
		chart.legend.enabled = true;
		chart.legend.marginLeft = 20;
		chart.legend.valueWidth = 20;
		chart.legend.periodValueText = "[[value.high]]";
		chart.write("chartToday");
});

AmCharts.ready(function () {
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartDataTomorrow;
                chart.categoryField = "country";
                chart.startDuration = 0;
                var categoryAxis = chart.categoryAxis;
                categoryAxis.gridPosition = "start"

                var graph = new AmCharts.AmGraph();
                graph.valueField = "visits";
                graph.balloonText = "<img src='https://www.google.com/s2/favicons?domain=https://www.wetteronline.de/'>[[category]]Uhr: <b>[[value]]%</b>";
                graph.type = "smoothedLine";
                graph.lineAlpha = 2;
		graph.lineThickness = 1;
                graph.fillAlphas = 0.2;
                graph.lineColor = "#6684fb";
                //graph.fillColor = "#6684fb";
		//graph.fillColors = ["#2A2A2A", "#6684fb"];
		graph.fillColors = ["#6684fb", "#2A2A2A"];
		
		var graphW = new AmCharts.AmGraph();
		graphW.valueField = "rainWC";
                graphW.type = "smoothedLine";
                graphW.balloonText = "<img src='https://www.google.com/s2/favicons?domain=https://www.wunderground.com/'></>[[category]]Uhr: <b>[[value]]%</b>";
		graphW.lineThickness = 1;
                graphW.fillAlphas = 0.2;
		graphW.lineColor ="#FFD30D";
		//graphW.fillColor ="#FFD30D";
		graphW.fillColors = ["#FFD30D", "#2A2A2A"];

		var graphX = new AmCharts.AmGraph();
		graphX.valueField = "rainWDe";
                graphX.type = "smoothedLine";
                graphX.balloonText = "<img src='https://www.google.com/s2/favicons?domain=https://www.wetter.de/'></>[[category]]Uhr: <b>[[value]]%</b>";
                graphX.lineAlpha = 1;
                graphX.lineThickness = 1;
                graphX.fillAlphas = 0.2;
		graphX.lineColor = "#24d442";
		graphX.fillColors = ["#24d442", "#2A2A2A"];

		var graphC = new AmCharts.AmGraph();
                graphC.valueField = "avg";
                graphC.balloonText = "<img src='https://www.google.com/s2/favicons?domain=https://docs.amcharts.com/'></>[[category]]Uhr: <b>[[value]]%</b>";
                graphC.type = "step";
                graphC.lineAlpha = 1;
                graphC.lineThickness = 3;
                graphC.fillAlphas = 0.2;
                graphC.lineColor = "#ffffff";
                graphC.fillColors = ["#FFFFFF", "#2A2A2A"];

		var graphT = new AmCharts.AmGraph();
                graphT.valueField = "temp";
                graphT.balloonText = "<b>[[value]] °C</b>";
                //graphC.showBalloonAt: "open";
                graphT.type = "smoothedLine";
                graphT.lineAlpha = 1;
                graphT.lineThickness = 2;
                graphT.fillAlphas = 0;
                graphT.lineColor = "#E6E600";		
		 
		graphX.hidden = true;
                chart.addGraph(graph);
		//chart.addGraph(graphC);
		chart.addGraph(graphX);	
		chart.addGraph(graphW);	
	        chart.addGraph(graphC);
		
		var yAxis = new AmCharts.ValueAxis();
                yAxis.position = "left";
                chart.addValueAxis(yAxis);
		yAxis.maximum = 95;
                yAxis.minimum = 0;

                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorAlpha = 0;
                chartCursor.zoomable = false;
                chartCursor.categoryBalloonEnabled = false;
                chart.addChartCursor(chartCursor);
                chart.creditsPosition = "top-right";
                chart.addClassNames = true;
                var legend = new AmCharts.AmLegend();
                chart.addLegend(legend);
                chart.legend.enabled = true;
                chart.legend.marginLeft = 20;
                chart.legend.valueWidth = 20;
                chart.legend.periodValueText = "[[value.high]]";
                chart.write("chartTomorrow");                
            });
AmCharts.ready(function () {
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartDataTT;
                chart.categoryField = "country";
                chart.startDuration = 0;
                var categoryAxis = chart.categoryAxis;
                categoryAxis.gridPosition = "start"

                var graph = new AmCharts.AmGraph();
                graph.valueField = "visits";
                graph.balloonText = "<img src='https://www.google.com/s2/favicons?domain=https://www.wetteronline.de/'>[[category]]Uhr: <b>[[value]]%</b>";
                graph.type = "smoothedLine";
                graph.lineAlpha = 2;
                graph.lineThickness = 1;
                graph.fillAlphas = 0.2;
                graph.lineColor = "#6684fb";
                //graph.fillColor = "#6684fb";
                //graph.fillColors = ["#2A2A2A", "#6684fb"];
                graph.fillColors = ["#6684fb", "#2A2A2A"];
                
                var graphW = new AmCharts.AmGraph();
                graphW.valueField = "rainWC";
                graphW.type = "smoothedLine";
                graphW.balloonText = "<img src='https://www.google.com/s2/favicons?domain=https://www.wunderground.com/'></>[[category]]Uhr: <b>[[value]]%</b>";
                graphW.lineThickness = 1;
                graphW.fillAlphas = 0.2;
                graphW.lineColor ="#FFD30D";
                //graphW.fillColor ="#FFD30D";
                graphW.fillColors = ["#FFD30D", "#2A2A2A"];
		
		var graphX = new AmCharts.AmGraph();
                graphX.valueField = "rainWDe";
                graphX.balloonText = "<img src='https://www.google.com/s2/favicons?domain=https://www.wetter.de/'></>[[category]]Uhr: <b>[[value]]%</b>";
                graphX.type = "smoothedLine";
                graphX.lineAlpha = 1;
		graphX.fillColors = ["#24d442", "#2A2A2A"];
                graphX.lineThickness = 1;
                graphX.fillAlphas = 0.2;
                graphX.lineColor = "#24d442";
		//graphX.fillColors = ["#2A2A2A", "#24d442"];

		var graphC = new AmCharts.AmGraph();
                graphC.valueField = "avg";
                graphC.balloonText = "<img src='https://www.google.com/s2/favicons?domain=https://docs.amcharts.com/'></>[[category]]Uhr: <b>[[value]]%</b>";
                graphC.type = "step";
                graphC.lineAlpha = 1;
                graphC.lineThickness = 3;
                graphC.fillAlphas = 0.2;
                graphC.lineColor = "#ffffff";
                graphC.fillColors = ["#FFFFFF", "#2A2A2A"];
		
		var graphT = new AmCharts.AmGraph();
                graphT.valueField = "temp";
                graphT.balloonText = "<b>[[value]] °C</b>";
                //graphC.showBalloonAt: "open";
                graphT.type = "smoothedLine";
                graphT.lineAlpha = 1;
                graphT.lineThickness = 2;
                graphT.fillAlphas = 0;
                graphT.lineColor = "#E6E600"

		graphX.hidden = true;
                //chart.addGraph(graphT);
		chart.addGraph(graph);		
		chart.addGraph(graphX);
		chart.addGraph(graphW);
                chart.addGraph(graphC);

		var yAxis = new AmCharts.ValueAxis();
                yAxis.position = "left";
                chart.addValueAxis(yAxis);
		yAxis.maximum = 95;
                yAxis.minimum = 0;

                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorAlpha = 0;
                chartCursor.zoomable = false;
                chartCursor.categoryBalloonEnabled = false;
                chart.addChartCursor(chartCursor);
                chart.creditsPosition = "top-right";
                chart.addClassNames = true;
                var legend = new AmCharts.AmLegend();
                chart.addLegend(legend);
                chart.legend.enabled = true;
                chart.legend.marginLeft = 20;
                chart.legend.valueWidth = 20;
                chart.legend.periodValueText = "[[value.high]]";
                chart.write("chartTT");
            });
        </script>
    </head>
    <body bgcolor="#2A2A2A">
	<!--<font face="veranda">Regenwahrscheinlichkeiten in München (blau = wetteronline.de, grün = wetter.de)</font><br><br> -->
	<h1 style="text-align:center;">Heute</h1><br>
<div id="chartToday" style="width: 100%; height: 400px;"></div>
	<h1 style="text-align:center;">Morgen</h1><br>
	<div id="chartTomorrow" style="width: 100%; height: 400px;"></div>
	<h1 style="text-align:center;">Übermorgen</h1><br>
	<div id="chartTT" style="width: 100%; height: 400px;"></div>
	<h1 style="text-align:center;">Wettercam</h1><br>
	<?php  
	$min = date('i');
	$min  = substr($min, 0, -1);
	$min = $min . "0";
	?>  
	<img src="https://www.foto-webcam.eu/webcam/muenchen/<?php echo date('o')."/".date('m')."/".date('j')."/".date('H'); echo $min;?>_hd.jpg" style="width:900px;display:block;margin-left:auto;margin-right:auto;">
     <br><br><br></body>
<meta http-equiv="refresh" content="600">
</html>
