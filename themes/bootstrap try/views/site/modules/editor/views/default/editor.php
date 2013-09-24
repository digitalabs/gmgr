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
<div class="accordion" id="accordion2">
<!--
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
        Enter Data
      </a>
    </div>
    <div id="collapseOne" class="accordion-body collapse">
      <div class="accordion-inner">
        <form class="form-inline" onsubmit="return graph()">

		<input type="text" name="feedurl[]" size="50" title="Add node"><a href="javascript:add_feed()">  <img title="Add child" size="20" src='/GMGR/images/plus.png'></a>
		<div id="newlink"></div> 
		<br><button type="button" class="btn btn-small btn-warning" onclick="graph()">Generate Graph</button>
			<!-- Template. This whole data will be added directly to working form above
			<div id="newlinktpl" style="display:none">  
				<div class="feed">  
				 &nbsp;&nbsp;&nbsp;<input title="Add node" type="text" name="feedurl[]" value=""  size="50">  <a href="javascript:add_feed()"><img title="Add child" size="20" src='/GMGR/images/plus.png'></a>
				</div>  
			</div>  
		</form>
      </div>
    </div>
  </div>
-->
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
		<input type="file" id="files"/><button type="button" class="btn btn-small btn-warning" onclick="graph()">Generate Graph</button>
		<div id="errDiv" class="alert-message warning"></div>
        
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
						<div id="graphDiv" style="width: 1000px; height: 495px;overflow-x:hidden;overflow-y:hidden;max-height:78%;vertical-align:center;background-color:#F8F8F8;">
							
						</div>
					</td>
					<td width="100%" style="vertical-align:center;">
						<div id="attDiv" style="vertical-align:center;text-align:top;position:absolute;top:0;">
							<br><font size="1px" style="vertical-align:top;color:#808080;">Click node to view information</font>
							<br>
							<table id="attTab" class="table table-hover"></table>
<!--
							<table id="attTab">
								<tr>
									<td style="vertical-align:top;">
										<font style="vertical-align:top;color:#808080;">Germplasm Characteristics</font><br>
										<?php 
											//$this->widget('bootstrap.widgets.TbDetailView', array(
											//'data'=>array('id'=>1, 'firstName'=>'Mark', 'lastName'=>'Otto', 'language'=>'CSS'),
											//'attributes'=>array(
												//array('name'=>'', 'label'=>'Name'),
												//array('name'=>'', 'label'=>'ID'),
												//array('name'=>'', 'label'=>'Method'),
												//array('name'=>'', 'label'=>'Location'),
												//array('name'=>'', 'label'=>'Creation Date'),
											//),
										//));
										?>
									</td>
								</tr>
							</table>
-->
						</div>
					</td>
				</tr>
			</table>
			
	  </div>
    </div>
  </div>
</div>

<script src="js/d3.v3.min.js"></script>
<script src="js/editor.js"></script>
</head>

<body>
	
</body>

</html>

