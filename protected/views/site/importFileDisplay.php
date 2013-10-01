 
 <h3>Germplasm List</h3>
    <p >
        <i><strong>Note:</strong>&nbsp; 
            Germplasm names <b>not</b> in <b>standardized</b> format are in <b>red color</b>.
        </i>
        <br><br>
    </p>
<span id="ajax-loading-indicator">
  <img src="./images/ajax-loader.gif" />
</span>
<?php /** @var BootActiveForm $form */ 
    $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'standardLink',
	'type'=>'horizontal',
	'htmlOptions'=>array('class'=>'well'),
)); 
//echo CHtml::beginForm();
//<input type="hidden" name="standardize" value="yes" />
CHtml::hiddenField('standardize','yes');
$this->widget('bootstrap.widgets.TbButton',array(
    'label' => 'Click to Standardize Germplasm',
    'type' => 'primary',
     'url'=> array('/site/output'),
     ));
?>


<div id="GermplasmList" class="GermplasmList">
</div>    
  <?php 
      //call output table
       //include( dirname(__FILE__). "/output.php");
  ?>
 <?php
  echo "<div id='table1'>";
  $this->widget('bootstrap.widgets.TbJsonGridView', array(
    'id' => 'pedigree',
    'type'=>'striped bordered condensed',
	'dataProvider'=>$arrayDataProvider,
	//'filter'=>$model,
	'template'=>"{items}{pager}",//strcmp($fremarks, 'in standardized format')
     'columns'=>array(
                array(
                    'header'=>'Cross Name',
                    'value'=>'CHtml::encode($data["nval"])',
                    'htmlOptions'=>array(
                        'style'=>'width:50px;',
                        'title'=>'tooltip sample'
                        )
                    ),
                array(
                    'header'=>'GID',
                    'value'=>'CHtml::encode($data["gid"])'
                    ),
               array(
                   'header'=>'Female Parent',
                   'type'=> 'raw',
                   'value'=> function ($data){
				     
					   if (strcmp(CHtml::encode($data["fremarks"]),"in standardized format")==0)
							return CHtml::encode($data["female"]);
						else
							return "<font style='color:#FF6600; font-weight:bold;'>".CHtml::tag("span", array("title"=>CHtml::encode($data["fremarks"]), "class"=>"tooltipster"),CHtml::encode($data["female"]))."</font>";
				   },		
               ),     
               array(
                   'header'=>'Male Parent',
                   'type'=> 'raw',
                   'value'=> function ($data){
					   if (strcmp(CHtml::encode($data["mremarks"]),"in standardized format")==0)
							return CHtml::encode($data["male"]);
						else
							return "<font style='color:#FF6600; font-weight:bold;'>".CHtml::tag("span", array("title"=>CHtml::encode($data["mremarks"]), "class"=>"tooltipster"),CHtml::encode($data["male"]))."</font>";
				   },
               ),
               array(
                    'header'=>'New GID',
                    'type'=> 'raw',
                    //'value'=>'CHtml::encode($data["mgid"])'
                    'value'=> function($data){
						    $your_array = array();
						    $your_array = explode("#", CHtml::encode($data["fgid"]));
							$your_array = implode("\n", $your_array);
							$fgid = $your_array;
							
							$your_array = array();
							$your_array = explode("#", CHtml::encode($data["mgid"]));
							$your_array = implode("\n", $your_array);
							$mgid = $your_array;
							
							return "<pre>".$fgid."</pre><pre>".$mgid."</pre>";
						}
               ),
      ),
	'template'=>'{summary}{items}{pager}',
    //'enablePagination' => true,
    'summaryText' => false,
    'cacheTTL' => 10, // cache will be stored 10 seconds (see cacheTTLType)
	'cacheTTLType' => 's', // type can be of seconds, minutes or hours
));

 echo "</div>";
?> 
<?php $this->endWidget(); 
//echo CHtml::endForm();
?>
<script type="text/javascript">
$(document).ready(function() {
 // $("#uploadFile").click(function (){
	$('#ajax-loading-indicator').bind('ajaxStart', function(){
     $(this).show();
    }).bind('ajaxStop', function(){
      $(this).hide();
	});
 // });
});
</script>