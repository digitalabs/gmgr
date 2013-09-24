<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Editor';
$this->breadcrumbs=array(
	'Editor',
);
Yii::import("ext.graphviz.components.*"); 
Yii::import("ext.graphviz.widgets.*");
?>

<html>
<head>
<meta charset="utf-8">
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/editor.css" />
<br><br><br><br>
<div class="span11">
<div class="accordion" id="accordion2">
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
        Read Sample Data
      </a>
    </div>
    <div id="collapseTwo" class="accordion-body">
      <div class="accordion-inner" id="fileIn">
<!--
        <input type="file" id="files"/><span id="errDiv"></span> <div id="errDiv"></div>
-->
        <input type="file" id="files"/><button type="button" class="btn btn-primary" onclick="graph()">Generate Graph</button>
        <div id="errDiv"></div>
        
      </div>
    </div>
  </div>
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
        Germplasm Pedigree
      </a>
    </div>
    <div id="collapseThree" class="accordion-body collapse">
      <div id="chart" style="height:520px;vertical-align:center;background-color:#FFF9F2;" >
<!--
            <iframe id="graphDiv" name="graphDiv"></iframe>
-->
    <!-- Scroll bar present but disabled -->
            <table style="vertical-align:center;">
                <tr>
                    <td width="50%">
                        <div id="graphDiv" style="width: 1080px; height: 495px;overflow-x:hidden;overflow-y:hidden;max-height:78%;vertical-align:center;background-color:#F8F8F8;">
                            
                        </div>
                    </td>
                    <td width="100%" style="vertical-align:center;">
                        <div id="attDiv" style="vertical-align:center;text-align:top;position:absolute;top:0;">
                            <br><font size="1px" style="vertical-align:top;color:#808080;">Click node to view information</font>
                            <br>
                            <table id="attTab" class="table table-hover"></table>

                        </div>
                    </td>
                </tr>
            </table>
            
      </div>
    </div>
  </div>
</div></div>
<script src="js/d3.v3.min.js"></script>
<script src="js/editor.js"></script>
</head>

<body>
	
</body>

</html>

