 <!--div to grey out the screen while loading indicator is on-->
<!--<div id='screen'>
</div>-->
<span id="ajax-loading-indicator">
</span>
<!---End for loading indicators-->
                  <?php
				   //<!----********************FOR  ASSIGN GID PORTION*******************--->
                     //include( dirname(__FILE__). "/assignGID.php");
					 //Yii::import('application.views.site.createddGID');
			       ?>  
<!---***********************************************FOR CreatedGID--Germplasm List, 2nd table, portion**********************************************-->
            </div id="germList">
            <h4 style=" border-bottom: 0px solid #999;text-align: left;">Germplasm List</h4> 
                     <?php
                        $this->widget('ext.selgridview.BootSelGridView', array(
                                 'id' => 'germplasmList',   
                                 'dataProvider' => $GdataProvider,
                                 'columns'=> array(
                                     array(
                                       'header'=>'Cross Name',
                                       'value'=>'CHtml::encode($data["nval"])',
                                     ), 
                                     array(
                                         'header'=>'GID',
                                         'value'=>'CHtml::encode($data["gid"])',
                                     ),
                                     array(
                                         'header'=>'Female Parent',
                                         'type'=>'raw',
                                         'value'=>function($data){
                                               $line = array();
                                               $line = explode("#", CHtml::encode($data["fremarks"]));
                                               $line = implode("\n", $line);
                                               $fremarks = $line;
                                               if (strcmp($fremarks, 'in standardized format') == 0) {
                                                
                                                   // return '<a style="color:black;" data-toggle="tooltip" data-placement="right" title="' .$fremarks. '">'.CHtml::encode($data["female"]).'</a>';
													return CHtml::tag("span", array("title"=>CHtml::encode($data["fremarks"]), "class"=>"tooltipster"),CHtml::encode($data["female"]));
                                                } else {
                                                    	return "<div class='j'><font style='color:#FF6600; font-weight:bold;'>".CHtml::link( CHtml::encode($data["female"]),
														Yii::app()->createUrl( "site/editGermplasm", array("germplasm"=>$data["female"],"error"=>$data["fremarks"])),array('title' => CHtml::encode($data["fremarks"]), 'class'=>'tooltipster'))."</font></div>";
                                                   // return '<a data-toggle="tooltip" title="' .CHtml::encode($data["mremarks"]) . '" data-placement="right" style="color:rgb(255, 0, 0); font-weight:bold;" href="/GMGR/index.php?r=site/editGermplasm.php?germplasm=' .CHtml::encode($data["female"]) . '&error=' .CHtml::encode($data["fremarks"]). '">' . CHtml::encode($data["female"]) . '<a>';
                                                }
                                         },
                                     ),
                                     array(
                                         'header'=>'Male Parent',
                                         'type'=>'raw',
                                         'value'=>function($data){
                                              $line = array();
                                              $line = explode("#", CHtml::encode($data["mremarks"]));
                                              $line = implode("\n", $line);
                                              $mremarks = $line;
                                               if (strcmp($mremarks, 'in standardized format') == 0) {
                                                   // return '<a style="color:black;" data-toggle="tooltip" data-placement="right" title="' . $mremarks . '">' . CHtml::encode($data["male"]) . '</a>';
												   return CHtml::tag("span", array("title"=>CHtml::encode($data["mremarks"]), "class"=>"tooltipster"),CHtml::encode($data["male"]));
                                                } else {
                                                   return "<div class='j'><font style='color:#FF6600; font-weight:bold;'>".CHtml::link( CHtml::encode($data["male"]),
													Yii::app()->createUrl( "site/editGermplasm", array("germplasm"=>$data["male"],"error"=>$data["mremarks"])),array('title' => CHtml::encode($data["mremarks"]), 'class'=>'tooltipster'))."</font></div>";
                                                    //echo '<a data-toggle="tooltip" data-placement="right" title="' . $mremarks . '" style="color:rgb(255, 0, 0); font-weight:bold;" href="/GMGR/index.php?r=site/editGermplasm.php?germplasm=' . CHtml::encode($data["male"]) . '&error=' . $mremarks . '">' . CHtml::encode($data["male"]) . '<a>';
                                                }
                                         },
                                     ),
                                     array(
                                         'header'=>'New GID',
                                         'type'=> 'raw',
                                         'value'=>function($data){
                                               $line = array();
                                               $line = explode("#", CHtml::encode($data["fgid"]));
                                               $line = implode("\n", $line);
                                               $fgid = $line;
                                                

                                               $line = array();
                                               $line = explode("#", CHtml::encode($data["mgid"]));
                                               $line = implode("\n", $line);
                                               $mgid = $line;
                                              
                                               return  '<pre>' . $fgid . '</pre><pre>' . $mgid . '</pre>';
                                         },
                                     ),
                                 ),
                         ));
                     ?>
 </div>
<?php //$this->endWidget();?>


              <?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'juiDialog',
                'options'=>array(
                    'title'=>'Show data',
                    'autoOpen'=>false,
                    'modal'=>true,
                    'width'=>'auto',
                    'height'=>'auto',
                ),
                ));
$this->endWidget();
?>
 <script type="text/javascript" src="assets/bootstrap.min.js"></script>
 <script type="text/javascript" src="./assets/pnotify-1.2.0/jquery.pnotify.js"></script>
<script type="text/javascript">
/*
$(document).ready(function() {
 //triggers  the activity loading indicator
  var pop = function(){
        $('#screen').css({ opacity: 0.4, 'width':$(document).width(),'height':$(document).height()});
        $('body').css({'overflow':'hidden'});
        $('#ajax-loading-indicator').css({'display': 'block'});
 }
 $('#ajaxSubmit').click(pop);
 
  var lastchecked = null;
  
  $('#selectedIds').click(function () {
        $('.selectedIds').prop('checked', this.checked);
    });
 
});*/


	
	
$(document).ajaxSend(function(event, request, settings) {
    $('#loading-indicator').show();
});

$(document).ajaxComplete(function(event, request, settings) {
    $('#loading-indicator').hide();
});
</script>
