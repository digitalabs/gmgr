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
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" />

	<br><br><br><br>    
        <div class="horizontal-form" style="text-align:right;vertical-align:right;margin-left:200px;margin-right: 5px;">    

                    
                    <div class="input-append">    
                      <input class="span2" id="appendedInputButtons" type="text" placeholder="Search Germplasm">
                      <button class="btn btn-primary" type="button" onclick="graph2();">GO</button>
                    </div>  
                             
        </div>
            
            
            <div style="vertical-align:middle;margin-left:5px;margin-right: 5px;">
                <div class="well" style="position:relative;border:1px solid; padding: 0px; height:670px">
                    <div id="graphDiv2" style="position:absolute;top:0px;right:0px;left:202px;overflow-x:scroll;overflow-y:scroll;padding: 0px; text-align: right; vertical-align: right; width: 910px; height: 630px;vertical-align:right;">
					<svg style="width:500px;height:800px;overflow-x:hidden;overflow-y:hidden;position:absolute;z-index:2;">
							<g id="vis"></g>
					</svg>
						<div id="graphDiv" width="5000" style="width:2000px;"></div>
					</div> 
                    <div class="div-gradient" style="padding-left: 0px; width: 200px;height:670px;text-align: justify;position:static;bottom:10px">
                        
                        <!--<div style="padding-left:5px;padding-right:5px;"><hr></div>-->
                        <br>
                        <div class="form-horizontal" style="padding: 5px;">
                            <input placeholder="All" style="width:50px;" value=" " id="maxStep" type="number" name="quantity" min="1" max="100"> 
							
                            <small><a data-toggle="tooltip" title="By default, a regular pedigree for a particular germplasm is created up to the certain number of known parents. You can, however, choose to show a smaller number of parental generations (steps), or to choose all." data-placement="right">Maximum Steps</a></small>
                        </div><br>
                        <label class="checkbox" style="padding-left: 30px;">
                          <small><input type="checkbox"><a data-placement="right" data-toggle="tooltip" title="Derivative and maintenance steps will be included in the pedigree.">Show Selection History</a></input></small><br>
                          <small><input type="checkbox"><a data-placement="right" data-toggle="tooltip" title="The pedigree graph can label its edges by the name of germplasm methods.">Show Method</a></input></small>
                        </label>
                        
                        <br><div style="padding-left: 5px;padding-right: 5px; text-align: right;"><button type="button" class="btn btn-mini btn-primary" onclick="graph2();">Load</button></div>
                        <div style="padding-left:5px;padding-right:5px;"><hr></div>
                        <center><div style="padding-left:5px;padding-right:5px;">Germplasm Information</div></center> <br>
                        <small>
                        <div style="padding-left:5px;padding-right:5px;"> 
                        <table style="border-collapse: separate !important;border-radius: 6px 6px 6px 6px;
							-moz-border-radius: 6px 6px 6px 6px;
							-webkit-border-radius: 6px 6px 6px 6px;
							box-shadow: 0 1px 1px #CCCCCC;" class="table table-hover table-condensed">
                            <tr><td width="50px" bgcolor="#0080FF" style="color: white;"><small>GID</td><td id="gid"  align="left" bgcolor="white" style=" vertical-align: left; text-align: left;"></td></tr>
                            <tr><td bgcolor="#0080FF" style="color: white;"><small>Name</td><td id="gname" bgcolor="white" style=" vertical-align: left; text-align: left;"></td></tr>
                            <tr><td bgcolor="#0080FF" style="color: white;"><small>Method</td><td id="gmethod" bgcolor="white" style=" vertical-align: left; text-align: left;"></td></tr>
                            <tr><td bgcolor="#0080FF" style="color: white;"><small>Method Type</td><td id="gmtype" bgcolor="white" style=" vertical-align: left; text-align: left;"></td></tr>
                            <tr><td bgcolor="#0080FF" style="color: white;"><small>Date</td><td id="gdate" bgcolor="white" style=" vertical-align: left; text-align: left;"></td></tr>
                            <tr><td bgcolor="#0080FF" style="color: white;"><small>Country</td><td id="gcountry" bgcolor="white" style=" vertical-align: left; text-align: left;"></td></tr>
                            <tr><td bgcolor="#0080FF" style="color: white;"><small>Location</td><td id="gloc" bgcolor="white" style=" vertical-align: left; text-align: left;"></td></tr>
                            <tr><td bgcolor="#0080FF" style="color: white;"><small>Crop Name</td><td id="gcname" bgcolor="white" style=" vertical-align: left; text-align: left;"></td></tr>
                            <tr><td bgcolor="#0080FF" style="color: white;"><small>Reference</td><td id="gref" bgcolor="white" style=" vertical-align: left; text-align: left;"></td></tr>
                            <tr><td bgcolor="#0080FF" style="color: white;"><small>GPID1</td><td id="gpid1" bgcolor="white" style=" vertical-align: left; text-align: left;"></td></tr>
                            <tr><td bgcolor="#0080FF" style="color: white;"><small>GPID2</td><td id="gpid2" bgcolor="white" style=" vertical-align: left; text-align: left;"></td></tr>
                        </table><br>  
                        <div style="vertical-align:top;text-align: left"><a style="color: white; text-decoration: none;"><img src='<?php echo Yii::app()->baseUrl;?>/images/legend.gif' width="185px" height="100px"></a></div> 
                        </div>   
                    </div>  
                       
                    <div style="vertical-align:right;text-align: right;">
                        <p style="position:absolute;bottom:0px;right:0px;padding:5px;"><span class="label label-warning">Note</span> <small>Apply the <i>changes</i> made by clicking the <b>Update</b> button.
                                                Click node to view germplasm information.</small>
                        </span>
                    </div>            
                </div>
            </div>
            
            <br><br>
        <!-- end editor content -->   
        
        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/bootstrap.min2.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/d3.v3.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/editor5.js"></script>
        <script type="text/javascript">
          $(document).ready(function(){
            $('.combobox').combobox();
          });
        </script>
	
<body>
	
</body>

</html>

