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
Yii::import('application.modules.curl');
Yii::import('application.modules.file_toArray');

if (isset($_GET['searchBtn'])) 
{
	//foo();
	echo "Success";
}


?>

<html>
<head>
<body>
<meta charset="utf-8">
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/editor.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" />

	<br><br> 
        <div style="text-align:right;vertical-align:right;margin-left:10px;margin-right: 10px;">       <br>

                    <!--<strong><small>Search Germplasm</small></strong>&nbsp;-->
                    <div style="z-index:4;position:fixed;top:75px;right:40px" class="input-append">    
                      <!--<div class="btn-group" data-toggle="buttons-checkbox">
                          <button type="button" class="btn">Name</button>
                          <button type="button" class="btn">GID</button>    
                      </div>-->
					  <form action="index.php?r=site/editor" method="post">
						<input title="This feature is a work in progress" style="width:140px;" class="span2" id="inputGID" name="inputGID" type="text" placeholder="Search Germplasm">
						<button name="searchBtn" id="searchBtn" title="This feature is a work in progress" class="btn btn-primary" onclick="validate()"type="submit">GO</button>
					  </form>
					  </div>  
                             
            </div>
			<span id="ajax-loading-indicator">
			</span>
			
            <!--bg while loading indicator is on-->
			<div style="z-index:100;" id='screen'>
			   
			</div>
            
           <!-- <div style="vertical-align:middle;margin-left:5px;margin-right: 5px;">
                <div class="well" style="position:relative;border:1px solid; padding: 0px; height:670px">
                    <div id="graphDiv2" style="position:absolute;top:0px;right:0px;left:202px;overflow-x:scroll;overflow-y:scroll;padding: 0px; text-align: right; vertical-align: right; width: 910px; height: 630px;vertical-align:right;">
					<!--<svg style="width:500px;height:800px;overflow-x:hidden;overflow-y:hidden;position:absolute;z-index:2;">
							<g id="vis"></g>
					</svg>
						<div id="graphDiv" width="5000" style="width:2000px;"></div>
					</div> -->
					<div id="graph">
					<svg width="1000" height="800" id="graphDiv"></svg>
					</div>
                    <!--<div id="graphDiv" style="z-index:50;">-->
					<!--<a href="#" id="generate">Generate download preview</a>	-->
					</div>
                    <div class="div-gradient" style="z-index:0;padding-left: 0px; width: 200px;height:650px;text-align: justify;position:fixed;right:40px;top:115px;bottom:20px;
														-webkit-box-shadow: 3px 3px 16px rgba(50, 50, 50, 0.75);
														-moz-box-shadow:    3px 3px 16px rgba(50, 50, 50, 0.75);
														box-shadow:         3px 3px 16px rgba(50, 50, 50, 0.75);">
                        
                        <!--<div style="padding-left:5px;padding-right:5px;"><hr></div>-->
                        <br>
                        <div class="form-horizontal" style="padding: 5px;">
                            <input title="This feature is a work in progress" placeholder="All" style="width:50px;" value=" " id="maxStep" type="number" name="quantity" min="1" max="100"> 
							
                            <small><a data-toggle="tooltip" title="By default, a regular pedigree for a particular germplasm is created up to the certain number of known parents. You can, however, choose to show a smaller number of parental generations (steps), or to choose all." data-placement="right">Maximum Steps</a></small>
                        </div><br>
                        <label class="checkbox" style="padding-left: 30px;">
                          <small><input title="This feature is a work in progress" type="checkbox"><a data-placement="right" data-toggle="tooltip" title="Derivative and maintenance steps will be included in the pedigree.">Show Selection History</a></input></small><br>
                          <small><input title="This feature is a work in progress" type="checkbox"><a data-placement="right" data-toggle="tooltip" title="The pedigree graph can label its edges by the name of germplasm methods.">Show Method</a></input></small>
                        </label>
                        
					<div style="padding-left: 5px;padding-right: 5px; text-align: right;">
							<button disabled="true" type="button" class="btn btn-mini btn-primary" onclick="graph2b();">Update</button>
							<button title="This feature is a work in progress" class="btn btn-mini btn-success" id="generate" value="" >Save image</button>
							<form method="POST" enctype="multipart/form-data" action="<?php echo Yii::app()->baseUrl;?>/save.php" id="myForm">
								<input type="hidden" name="img_val" id="img_val" value="" />
							</form>
							
                        <div style="padding-left:5px;padding-right:5px;"><hr></div>
                        <center><div style="padding-left:5px;padding-right:5px;">Germplasm Information</div></center> <br>
						
						<!--<div style="position:fixed;top:20px;left:40px;padding:5px;">
							<button title="This feature is a work in progress" class="btn btn-mini btn-success" id="savePNG" value="">Edit</button>
						</div>-->
					
                        <small>
                        <div style="padding-left:5px;padding-right:5px;"> 
                        <table style="border-collapse: separate !important;border-radius: 6px 6px 6px 6px;
							-moz-border-radius: 6px 6px 6px 6px;
							-webkit-border-radius: 6px 6px 6px 6px;
							box-shadow: 0 1px 1px #CCCCCC;
							
							" class="table table-hover table-condensed">
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
                        </table>
                        <div style="vertical-align:top;text-align: left"><a style="color: white; text-decoration: none;"><img src='images/legend.gif' width="185px" height="100px"></a></div> 
                        </div>   
                    </div>  
					
                    
					
                    <div style="vertical-align:right;text-align: right;">
						
                        <p style="position:fixed;bottom:0px;left:40px;padding:5px;"><span class="label label-warning">Note</span> 
							<small>Apply the <i>changes</i> made by clicking the <b>Update</b> button.
                                                Click node to view germplasm information.</small>
                        </span>
                    </div>            
                </div>
				<div style="position:fixed;left:40px;top:80px;">
					<!--<span>Zoom</span>-->
					<select class="selectpicker" style="width:80px" width="50px" id='zooming' onchange="zoomings (this);">
						<option value="100%"  selected="selected">Zoom</option>
						<option value="100%">------------</option>
						<option value="25%">25%</option>
						<option value="50%">50%</option>
						<option value="75%">75%</option>
						<option value="100%">100%</option>
						<option value="150%">150%</option>
						<option value="200%">200%</option>
						<option value="250%">250%</option>
						<option value="300%">300%</option>
					</select>
					<!--<button class="btn btn-mini btn-success" id="generate" value="">Save as PNG</button>
					<a href="#" id="generate">Download preview</a>-->
				</div> 
            <!--</div>-->
            <br><br><br><br><br><br>
        <!-- end editor content -->
		
				<!-- Hidden <FORM> to submit the SVG data to the server, which will convert it to SVG/PDF/PNG downloadable file.
			 The form is populated and submitted by the JavaScript below. -->
		<form id="svgform" method="post" action="download.pl">
		 <input type="hidden" id="output_format" name="output_format" value="">
		 <input type="hidden" id="data" name="data" value="">
		</form>
        
        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/d3.v3.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/editor4.js"></script>
		<!--<script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/editor5b.js"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/prettify/188.0.0/prettify.js"></script>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
		<script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/html2canvas.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/jquery.plugin.html2canvas.js"></script>
		<script src="<?php echo Yii::app()->baseUrl;?>/js/vkbeautify.0.99.00.beta.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/rgbcolor.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/canvg.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/svgenie.js"></script>
        <script type="text/javascript">
		
			function capture() {
				$('#graph').html2canvas({
					onrendered: function (svg) {
						//Set hidden field's value to image data (base-64 string)
						$('#img_val').val(svg.toDataURL("image/png"));
						//Submit the form manually
						document.getElementById("myForm").submit();
					}
				});
			} 
			
			/*function validate()
			{
				if(document.getElementById('searchBtn')=='' || document.getElementById('searchBtn')==' ' || document.getElementById('searchBtn')=='Search Germplasm')
				alert('Error');
			}
            $(document).ready(function() {
				$("#savePNG").click(function () {
					html2canvas($("#graphDiv"), {
						background: "red",
						width:1000,
						height:1000,
						onrendered: function (canvas) {
							var imgSrc = canvas.toDataURL();
							var popup = window.open(imgSrc);
						}
					});
				});
			});*/
			/*$(document).ready(function() {
				$("#generate").click(function () {
					alert('test');
					writeDownloadLink();
				});
				//create_d3js_drawing();

				// on first visit, randomize the positions & colors
				//randomize.click();

				// Attached actions to the buttons
				//$("#show_svg_code").click(function() { show_svg_code(); });

				//$("#save_as_svg").click(function() { submit_download_form("svg"); });

				//$("#save_as_pdf").click(function() { submit_download_form("pdf"); });

				$("#savePNG").click(function() { submit_download_form("png"); });
			});*/
			window.onclick = function(){
				svgenie.save( document.getElementById('graphDiv'), { name:"this.png" } ); 
			}
			
			function zoomings(optionSel)
			{
				var OptionSelected = optionSel.selectedIndex;
				var val = optionSel.options[OptionSelected].text;
				//alert(val);
				var div = document.getElementById ("graphDiv");
				div.style.zoom = val;
			}
			
			$(document).ready(function() {
			  var pop = function(){
					$('#screen').css({ opacity: 0.4, 'width':$(document).width(),'height':$(document).height()});
					$('body').css({'overflow':'hidden'});
					$('#ajax-loading-indicator').css({'display': 'block'});
			 }
			 $('#searchBtn').click(pop);

			});
			s
			
			<?php
			Yii::app()->clientScript->registerScript(
			   'myHideEffect',
			   '$(".info").animate({opacity: 1.0}, 3000).fadeOut("slow");',
			   CClientScript::POS_READY
			);
			?>
			
			
        </script>

</body>

</html>

