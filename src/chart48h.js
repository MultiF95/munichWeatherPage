// use dark theme
am4core.useTheme(am4themes_dark);
//am4core.useTheme(am4themes_animated);

// create charts
var percChart48h = am4core.create("percChart48h", am4charts.XYChart);
var tempChart48h = am4core.create("tempChart48h", am4charts.XYChart);
percChart48h.hiddenState.properties.opacity = 0;
tempChart48h.hiddenState.properties.opacity = 0;

// get json
$.getJSON('https://api.weather.com/v2/turbo/vt1hourlyforecast?apiKey=d522aa97197fd864d36b418f39ebb323&format=json&geocode=48.14%2C11.58&language=de-DE&units=m', function(data) {
  var obj = [];
  for (var i = 0; i <= 48; i++) {
    var date = new Date(data.vt1hourlyforecast.processTime[i]);
    var editedDate = date.getHours();
    //alert(editedDate)
    obj.push({
      time: date,
      perc: data.vt1hourlyforecast.precipPct[i],
      temp: data.vt1hourlyforecast.temperature[i]
    });
  }
  tempChart48h.data = obj;
  percChart48h.data = obj;

});

// temperature chart 48
var tempDateAxis = tempChart48h.xAxes.push(new am4charts.DateAxis());
tempDateAxis.renderer.grid.template.location = 0;
tempDateAxis.renderer.minGridDistance = 70;
tempDateAxis.tooltip.disabled = true;
tempDateAxis.baseInterval = {
  "timeUnit": "hours",
  "count": 1
};

var tempValueAxis = tempChart48h.yAxes.push(new am4charts.ValueAxis());
tempValueAxis.tooltip.disabled = true;
tempValueAxis.max = 20;
tempValueAxis.renderer.minGridDistance = 20;

var tempSeries = tempChart48h.series.push(new am4charts.LineSeries());
//tempSeries.tensionX = 0.8;
//tempSeries.tensionY = 1;
tempSeries.stroke = am4core.color("#ffcc00");
tempSeries.dataFields.dateX = "time";
tempSeries.dataFields.valueY = "temp";
tempSeries.noRisers = false;
tempSeries.strokeWidth = 2;
tempSeries.sequencedInterpolation = false;
tempSeries.minHeight = 20;
tempSeries.tooltipText = "{valueY.value} °C";
tempSeries.fillOpacity = .2;
tempSeries.fill = am4core.color("#ffcc00");
tempChart48h.cursor = new am4charts.XYCursor();

// percentage chart 48
var percDateAxis = percChart48h.xAxes.push(new am4charts.DateAxis());
percDateAxis.renderer.grid.template.location = 0;
percDateAxis.renderer.minGridDistance = 50;
percDateAxis.tooltip.disabled = true;
percDateAxis.baseInterval = {
  "timeUnit": "hours",
  "count": 1
};

var percValueAxis = percChart48h.yAxes.push(new am4charts.ValueAxis());
percValueAxis.tooltip.disabled = true;
percValueAxis.max = 90;
percValueAxis.renderer.minGridDistance = 30;

var percSeries = percChart48h.series.push(new am4charts.StepLineSeries());
percSeries.tooltipText = "{valueY.value} %";
percSeries.fillOpacity = 0.2;
percSeries.dataFields.dateX = "time";
percSeries.dataFields.valueY = "perc";
percSeries.strokeWidth = 2;
percSeries.sequencedInterpolation = true;
percChart48h.cursor = new am4charts.XYCursor();
