<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	title: {
		text: "Number of medical cases per patient"
	},
	axisY: {
		title: "Number of Medical Cases"
	},
  axisX:{
    title:"Patient ID"
  },
	data: [{
		type: "line",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();

}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
