 <br>
 <h3>Germplasm List</h3>
    <p >
        <i><strong>Note:</strong>&nbsp; 
            Germplasm names <b>not</b> in <b>standardized</b> format are in <b>red color</b>.
        </i>
        <br><br>
    </p>
<!--div to grey out the screen while loading indicator is on-->
<div id='screen'>
</div>
<span id="ajax-loading-indicator">
</span>
<!---End for loading indicators-->

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
	'id'=> 'btnStandard',
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
 // echo "<div id='table1'>";
  $this->widget('ext.selgridview.BootSelGridView', array(
   'id' => 'pedigreeGrid',
   'dataProvider' => $dataProvider,
   'filter' => $filtersForm,
   'selectableRows' => 10,
   //'enablePagination' => true,
   'columns'=>array(
                array(
                    'header'=>'Cross Name',
					'name' => '',
					'type' => 'raw',
                    'value'=>'CHtml::encode($data["nval"])',
					 'filter' => CHtml::textField('FilterPedigreeForm[nval]', isset($_GET['FilterPedigreeForm']['nval]']) ? $_GET['FilterPedigreeForm']['nval'] : ''),
                    'htmlOptions'=>array(
                        'style'=>'width:50px;',
                        )
                    ),
                array(
                    'header'=>'GID',
					'name' => 'gid',
					'type' => 'raw',
                    'value'=>'CHtml::encode($data["gid"])'
                    ),
               array(
                   'header'=>'Female Parent',
				   'name' => 'female',
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
				   'name' => 'male',
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

  
));

 //echo "</div>";
?> 

<script type="text/javascript">
$(document).ready(function() {
  var pop = function(){
        $('#screen').css({ opacity: 0.4, 'width':$(document).width(),'height':$(document).height()});
        $('body').css({'overflow':'hidden'});
        $('#ajax-loading-indicator').css({'display': 'block'});
 }
 $('#btnStandard').click(pop);
});
</script>
<?php $this->endWidget(); 
//echo CHtml::endForm();
?>