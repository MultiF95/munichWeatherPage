// use dark theme
am4core.useTheme(am4themes_dark);
am4core.useTheme(am4themes_animated);

// create charts
var percChart48 = am4core.create("percChart48", am4charts.XYChart);
var tempChart48 = am4core.create("tempChart48", am4charts.XYChart);
var percChart10 = am4core.create("percChart10", am4charts.XYChart);
var tempChart10 = am4core.create("tempChart10", am4charts.XYChart);
percChart48.hiddenState.properties.opacity = 0;
tempChart48.hiddenState.properties.opacity = 0;
percChart10.hiddenState.properties.opacity = 0;
tempChart10.hiddenState.properties.opacity = 0;

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
  tempChart48.data = obj;
  percChart48.data = obj;
});

// same for the 10 day tables
$.getJSON('https://api.weather.com/v2/turbo/vt1dailyforecast?apiKey=d522aa97197fd864d36b418f39ebb323&format=json&geocode=48.14%2C11.58&language=de-DE&units=m', function(data) {
  var obj = [];
  for (var i = 1; i <= 14; i++) {
    var date = new Date(data.vt1dailyforecast.validDate[i]);
    var editedDate = moment(date).format('DD.MM');
    //alert(editedDate)
    obj.push({
      time: date,
      perc: data.vt1dailyforecast.day.precipPct[i],
      tempDay: data.vt1dailyforecast.day.temperature[i],
      tempNight: data.vt1dailyforecast.night.temperature[i]
    });
  }
  console.log(obj)
  tempChart10.data = obj;
  percChart10.data = obj;
});

// temperature chart 48
var tempDateAxis = tempChart48.xAxes.push(new am4charts.DateAxis());
tempDateAxis.renderer.grid.template.location = 0;
tempDateAxis.renderer.minGridDistance = 50;
tempDateAxis.tooltip.disabled = true;
tempDateAxis.baseInterval = {
  "timeUnit": "hours",
  "count": 1
};

var tempValueAxis = tempChart48.yAxes.push(new am4charts.ValueAxis());
tempValueAxis.tooltip.disabled = true;
tempValueAxis.max = 20;
tempValueAxis.renderer.minGridDistance = 20;

var tempSeries = tempChart48.series.push(new am4charts.LineSeries());
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
tempChart48.cursor = new am4charts.XYCursor();

// temperature chart 10
var tempDateAxis = tempChart10.xAxes.push(new am4charts.DateAxis());
tempDateAxis.renderer.grid.template.location = 0;
tempDateAxis.renderer.minGridDistance = 50;
tempDateAxis.tooltip.disabled = true;
/*tempDateAxis.baseInterval = {
  "timeUnit": "days",
  "count": 10
};*/

var tempValueAxis10 = tempChart10.yAxes.push(new am4charts.ValueAxis());
tempValueAxis10.tooltip.disabled = true;
tempValueAxis10.max = 20;
tempValueAxis10.renderer.minGridDistance = 20;

var tempSeries10Day = tempChart10.series.push(new am4charts.LineSeries());
tempSeries10Day.tensionX = 0.8;
tempSeries10Day.tensionY = 1;
tempSeries10Day.stroke = am4core.color("#ff0000");
tempSeries10Day.dataFields.dateX = "time";
tempSeries10Day.dataFields.valueY = "tempDay";
tempSeries10Day.noRisers = false;
tempSeries10Day.strokeWidth = 2;
tempSeries10Day.sequencedInterpolation = false;
tempSeries10Day.minHeight = 20;
tempSeries10Day.tooltipText = "{valueY.value} °C";
tempSeries10Day.fillOpacity = 0;
tempSeries10Day.fill = am4core.color("#ff0000");

var tempSeries10Night = tempChart10.series.push(new am4charts.LineSeries());
tempSeries10Night.tensionX = 0.8;
tempSeries10Night.tensionY = 1;
tempSeries10Night.stroke = am4core.color("#1a75ff");
tempSeries10Night.dataFields.dateX = "time";
tempSeries10Night.dataFields.valueY = "tempNight";
tempSeries10Night.noRisers = false;
tempSeries10Night.strokeWidth = 2;
tempSeries10Night.sequencedInterpolation = false;
tempSeries10Night.minHeight = 20;
tempSeries10Night.tooltipText = "{valueY.value} °C";
tempSeries10Night.fillOpacity = 0;
tempSeries10Night.fill = am4core.color("#1a75ff");

tempChart10.cursor = new am4charts.XYCursor();

// percentage chart 48
var percDateAxis = percChart48.xAxes.push(new am4charts.DateAxis());
percDateAxis.renderer.grid.template.location = 0;
percDateAxis.renderer.minGridDistance = 50;
percDateAxis.tooltip.disabled = true;
percDateAxis.baseInterval = {
  "timeUnit": "hours",
  "count": 1
};

var percValueAxis = percChart48.yAxes.push(new am4charts.ValueAxis());
percValueAxis.tooltip.disabled = true;
percValueAxis.max = 90;
percValueAxis.renderer.minGridDistance = 30;

var percSeries = percChart48.series.push(new am4charts.StepLineSeries());
percSeries.tooltipText = "{valueY.value} %";
percSeries.fillOpacity = 0.2;
percSeries.dataFields.dateX = "time";
percSeries.dataFields.valueY = "perc";
percSeries.strokeWidth = 2;
percSeries.sequencedInterpolation = true;
percChart48.cursor = new am4charts.XYCursor();

// percentage chart 10
var percDateAxis10 = percChart10.xAxes.push(new am4charts.DateAxis());
percDateAxis10.renderer.grid.template.location = 0;
percDateAxis10.renderer.minGridDistance = 50;
percDateAxis10.tooltip.disabled = true;
/*percDateAxis10.baseInterval = {
  "timeUnit": "hours",
  "count": 1
};*/

var percValueAxis10 = percChart10.yAxes.push(new am4charts.ValueAxis());
percValueAxis10.tooltip.disabled = true;
percValueAxis10.max = 90;
percValueAxis10.renderer.minGridDistance = 30;

var percSeries10 = percChart10.series.push(new am4charts.ColumnSeries());
percSeries10.tooltipText = "{valueY.value} %";
percSeries10.fillOpacity = 0.5;
percSeries10.dataFields.dateX = "time";
percSeries10.dataFields.valueY = "perc";
percSeries10.strokeWidth = 0;
percSeries10.sequencedInterpolation = true;
percChart10.cursor = new am4charts.XYCursor();
