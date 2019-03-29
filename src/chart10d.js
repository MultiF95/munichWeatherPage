// use dark theme
am4core.useTheme(am4themes_dark);
//am4core.useTheme(am4themes_animated);

// create charts
var percChart10d = am4core.create("percChart10d", am4charts.XYChart);
var tempChart10d = am4core.create("tempChart10d", am4charts.XYChart);
percChart10d.hiddenState.properties.opacity = 0;
tempChart10d.hiddenState.properties.opacity = 0;

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
  tempChart10d.data = obj;
  percChart10d.data = obj;
});


// temperature chart 10
var tempDateAxis = tempChart10d.xAxes.push(new am4charts.DateAxis());
tempDateAxis.renderer.grid.template.location = 0;
tempDateAxis.renderer.minGridDistance = 75;
tempDateAxis.tooltip.disabled = true;
/*tempDateAxis.baseInterval = {
  "timeUnit": "days",
  "count": 10
};*/

var tempValueAxis = tempChart10d.yAxes.push(new am4charts.ValueAxis());
tempValueAxis.tooltip.disabled = true;
tempValueAxis.max = 20;
tempValueAxis.renderer.minGridDistance = 20;

var tempSeriesDay = tempChart10d.series.push(new am4charts.LineSeries());
tempSeriesDay.tensionX = 0.8;
tempSeriesDay.tensionY = 1;
tempSeriesDay.stroke = am4core.color("#ff0000");
tempSeriesDay.dataFields.dateX = "time";
tempSeriesDay.dataFields.valueY = "tempDay";
tempSeriesDay.noRisers = false;
tempSeriesDay.strokeWidth = 2;
tempSeriesDay.sequencedInterpolation = false;
tempSeriesDay.minHeight = 20;
tempSeriesDay.tooltipText = "{valueY.value} °C";
tempSeriesDay.fillOpacity = 0;
tempSeriesDay.fill = am4core.color("#ff0000");

var tempSeriesNight = tempChart10d.series.push(new am4charts.LineSeries());
tempSeriesNight.tensionX = 0.8;
tempSeriesNight.tensionY = 1;
tempSeriesNight.stroke = am4core.color("#1a75ff");
tempSeriesNight.dataFields.dateX = "time";
tempSeriesNight.dataFields.valueY = "tempNight";
tempSeriesNight.noRisers = false;
tempSeriesNight.strokeWidth = 2;
tempSeriesNight.sequencedInterpolation = false;
tempSeriesNight.minHeight = 20;
tempSeriesNight.tooltipText = "{valueY.value} °C";
tempSeriesNight.fillOpacity = 0;
tempSeriesNight.fill = am4core.color("#1a75ff");

tempChart10d.cursor = new am4charts.XYCursor();

// percentage chart 10
var percDateAxis = percChart10d.xAxes.push(new am4charts.DateAxis());
percDateAxis.renderer.grid.template.location = 0;
percDateAxis.renderer.minGridDistance = 75;
percDateAxis.tooltip.disabled = true;
/*percDateAxis10.baseInterval = {
  "timeUnit": "hours",
  "count": 1
};*/

var percValueAxis = percChart10d.yAxes.push(new am4charts.ValueAxis());
percValueAxis.tooltip.disabled = true;
percValueAxis.max = 90;
percValueAxis.renderer.minGridDistance = 30;

var percSeries = percChart10d.series.push(new am4charts.ColumnSeries());
percSeries.tooltipText = "{valueY.value} %";
percSeries.fillOpacity = 0.5;
percSeries.dataFields.dateX = "time";
percSeries.dataFields.valueY = "perc";
percSeries.strokeWidth = 0;
percSeries.sequencedInterpolation = true;
percChart10d.cursor = new am4charts.XYCursor();
