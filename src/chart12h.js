// use dark theme
am4core.useTheme(am4themes_dark);
//am4core.useTheme(am4themes_animated);

// create chart
var percChart12h = am4core.create("percChart12h", am4charts.XYChart);
var tempChart12h = am4core.create("tempChart12h", am4charts.XYChart);
percChart12h.hiddenState.properties.opacity = 0;
tempChart12h.hiddenState.properties.opacity = 0;

// get json
$.getJSON('https://api.weather.com/v2/turbo/vt1hourlyforecast?apiKey=d522aa97197fd864d36b418f39ebb323&format=json&geocode=48.14%2C11.58&language=de-DE&units=m', function(data) {
  var obj = [];
  for (var i = 0; i <= 12; i++) {
    var date = new Date(data.vt1hourlyforecast.processTime[i]);
    var editedDate = date.getHours();
    obj.push({
      time: date,
      perc: data.vt1hourlyforecast.precipPct[i],
      temp: data.vt1hourlyforecast.temperature[i]
    });
  }
  tempChart12h.data = obj;
  percChart12h.data = obj;
});


// temperature chart
var tempDateAxis = tempChart12h.xAxes.push(new am4charts.DateAxis());
tempDateAxis.renderer.minGridDistance = 10;
tempDateAxis.dateFormats.setKey("hour", "HH");

var tempValueAxis = tempChart12h.yAxes.push(new am4charts.ValueAxis());
tempValueAxis.extraMax = 1;
tempValueAxis.hide();

var tempSeries = tempChart12h.series.push(new am4charts.LineSeries());
tempSeries.stroke = am4core.color("#ffcc00");
tempSeries.dataFields.dateX = "time";
tempSeries.dataFields.valueY = "temp";
tempSeries.strokeWidth = 2;
tempSeries.fillOpacity = .2;
tempSeries.fill = am4core.color("#ffcc00");
tempSeries.clickable = false;

// percentage chart
var percDateAxis = percChart12h.xAxes.push(new am4charts.DateAxis());
percDateAxis.renderer.minGridDistance = 10;
percDateAxis.dateFormats.setKey("hour", "HH");

var percValueAxis = percChart12h.yAxes.push(new am4charts.ValueAxis());
percValueAxis.min = 0;
percValueAxis.max = 100;
percValueAxis.strictMinMax = true;
percValueAxis.hide();

var percSeries = percChart12h.series.push(new am4charts.StepLineSeries());
percSeries.tooltipText = "{valueY.value} %";
percSeries.fillOpacity = 0.2;
percSeries.dataFields.dateX = "time";
percSeries.dataFields.valueY = "perc";
percSeries.strokeWidth = 2;
percSeries.noRisers = true;
percSeries.fill = am4core.color("#4ca6ff");
percSeries.stroke = am4core.color("#4ca6ff");
percChart12h.clickable = false;

var lbT = tempSeries.bullets.push(new am4charts.LabelBullet());
lbT.label.text = "{valueY}";
lbT.label.fill = am4core.color("#ffcc00");
lbT.padding(-15, 0, 0, 0);

var lBP = percSeries.bullets.push(new am4charts.LabelBullet());
lBP.label.text = "{valueY}";
lBP.label.fill = am4core.color("#4ca6ff");
lBP.padding(-10, 0, 0, 0);
