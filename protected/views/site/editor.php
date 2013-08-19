<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Editor';
$this->breadcrumbs=array(
	'Editor',
);
?>

<html>
  <head>
  

    <script type='text/javascript' src='https://www.google.com/jsapi'></script>
    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['orgchart']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
		var x=document.getElementById('myText');
		//String[] results = x.split(",");
        var data = new google.visualization.DataTable();
		alert(x.value);
		
        data.addColumn('string', 'Name');
        data.addColumn('string', 'Manager');
        //data.addColumn('string', 'ToolTip');
		
		
        data.addRows([
          ['A', ''],
          ['B', 'A'],
          ['E', 'A'],
          ['C', 'B'],
          ['D', 'C']
        ]);
        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
        chart.draw(data, {allowHtml:true});
      }
    </script>
  </head>

  <body>
	Test Input 
	<br><input type='text' id='myText' />
	<br><input type='button' onclick='drawChart()' value='Ok' />
	<hr>
    <div id='chart_div'></div>
  </body>
</html>
