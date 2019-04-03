// use dark theme
am4core.useTheme(am4themes_dark);
//am4core.useTheme(am4themes_animated);

// create charts
var percChart10d = am4core.create("percChart10d", am4charts.XYChart);
percChart10d.language.locale = am4lang_de_DE;
var tempChart10d = am4core.create("tempChart10d", am4charts.XYChart);
tempChart10d.language.locale = am4lang_de_DE;

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
tempDateAxis.dateFormats.setKey("day", "EEEEE");
tempDateAxis.tooltip.disabled = true;
tempDateAxis.renderer.minGridDistance = 10;

var tempValueAxis = tempChart10d.yAxes.push(new am4charts.ValueAxis());
tempValueAxis.tooltip.disabled = true;
tempValueAxis.max = 20;
tempValueAxis.hide();

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

tempChart10d.clickable = false;

// percentage chart 10
var percDateAxis = percChart10d.xAxes.push(new am4charts.DateAxis());
percDateAxis.renderer.minGridDistance = 10;
percDateAxis.dateFormats.setKey("day", "EEEEE");
percDateAxis.tooltip.disabled = true;

var percValueAxis = percChart10d.yAxes.push(new am4charts.ValueAxis());
percValueAxis.tooltip.disabled = true;
percValueAxis.max = 90;
percValueAxis.renderer.minGridDistance = 30;
percValueAxis.hide();

var percSeries = percChart10d.series.push(new am4charts.ColumnSeries());
percSeries.tooltipText = "{valueY.value} %";
percSeries.fillOpacity = 0.2;
percSeries.dataFields.dateX = "time";
percSeries.dataFields.valueY = "perc";
percSeries.strokeWidth = 1;
percSeries.fill = am4core.color("#4ca6ff");
percSeries.stroke = am4core.color("#4ca6ff");
percSeries.sequencedInterpolation = true;
percChart10d.clickable = false;

var lbTD = tempSeriesDay.bullets.push(new am4charts.LabelBullet());
lbTD.label.text = "{valueY}";
lbTD.label.fill = am4core.color("#ff0000");
lbTD.padding(-25, 0, 0, 0);

var lbTN = tempSeriesNight.bullets.push(new am4charts.LabelBullet());
lbTN.label.text = "{valueY}";
lbTN.label.fill = am4core.color("#1a75ff");
lbTN.padding(-25, 0, 0, 0);

var lbP = percSeries.bullets.push(new am4charts.LabelBullet());
lbP.label.text = "{valueY}";
lbP.label.fill = am4core.color("#4ca6ff");
lbP.padding(-10, 0, 0, 0);
