<!--div to grey out the screen while loading indicator is on-->
<div id='screen'>
</div>
<span id="ajax-loading-indicator">
</span>
<!---End for loading indicators-->

<div id="tableEvent"></div>

 <br>
 <div id='table1'>
	     <h3>Germplasm List</h3>
    <i><p ><strong>Note:</strong>&nbsp; 
            Germplasm names not in standardized format are in red color.Hover the mouse over the germplasm names to see the error and click to correct it.
        </p></i>
		
<?php
   //Dropdown Pagination
   $pageSize=Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']); 
?>
<br>
<!--<div class="selection">
	<button onclick="Ext.getCmp('test_grid').getPlugin('pagingSelectionPersistence').clearPersistedSelection()">Clear All</button>
	<button onclick="return selectAll()">Select All</button>
</div>-->
 <?php
 
 $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'type' => 'horizontal',
	'id' => 'assign-gid-form',
	'action' => array('/site/assignGID'),
 ));
 $this->widget('ext.selgridview.BootSelGridView', array(
     'id' => 'pedigreeGrid',
	 'dataProvider'=> $dataProvider,
	 'filter'=>$filtersForm,
	 'selectableRows' => 2,
     'columns'=>array(
                array(
                    'id' => 'selectedIds',  //checked[]
                    'class'=>'CCheckBoxColumn', 
                    ),
                array(
                    'header'=>'Cross Name', 
                    'value'=>'CHtml::encode($data["nval"])',
                    'name'=>'',
                    //'filter'=>CHtml::textField('FilterPedigreeForm[nval]'),
                    'filter'=>CHtml::textField('FilterPedigreeForm[nval]',isset($_GET['FilterPedigreeForm']['nval]'])? $_GET['FilterPedigreeForm']['nval']:''),
                    'htmlOptions'=>array(
                        'style'=>'width:50px;',
                        )
                ),
                array(
                    'header'=>'GID',
                    'value'=>'CHtml::encode($data["gid"])',
                    'name'=>'gid',
                   //'filter'=>CHtml::textField('FilterPedigreeForm[gid]'),
                   'filter'=>CHtml::textField('FilterPedigreeForm[gid]',isset($_GET['FilterPedigreeForm']['gid]'])? $_GET['FilterPedigreeForm']['gid']:''),
                    ),
                      array(
                   'header'=>'Female Parent',
                   'name'=>'female',
                   'type'=> 'raw', 
                   /*'value'=>'CHtml::link( CHtml::encode($data["female"]),
							Yii::app()->createUrl( "site/editGermplasm", array("germplasm"=>$data["female"]) ))',*/
                   'value'=> function ($data){
					   if (strcmp(CHtml::encode($data["fremarks"]),"in standardized format")==0){
					               
							return CHtml::encode($data["female"]);        // CHtml::hiddenField('hiddenFid',CHtml::encode($data["female"]));   
                                           }
					 else{
							//return "<font style='color:#FF6600; font-weight:bold;'>".CHtml::encode($data["female"])."</font>";
							 
							return "<div class='j'><font style='color:#FF6600; font-weight:bold;'>".CHtml::link( CHtml::encode($data["female"]),
							Yii::app()->createUrl( "site/editGermplasm", array("germplasm"=>$data["female"],"error"=>$data["fremarks"])),array('title' => CHtml::encode($data["fremarks"]), 'class'=>'tooltipster'))."</font></div>";
                                                        
                                                        //echo CHtml::hiddenField('hiddenFid',CHtml::encode($data["female"]));
                        }
						

                                        
				   },
							
               ),     
               array(
                   'header'=>'Male Parent',
                   'name'=>'male',
                   'type'=> 'raw',
                   'value'=> function ($data){
					   if (strcmp(CHtml::encode($data["mremarks"]),"in standardized format")==0){
					       //echo CHtml::hiddenField('hiddenMid',CHtml::encode($data["male"]));
							return CHtml::encode($data["male"]);
						}
						else{
							//echo CHtml::hiddenField('hiddenMid',CHtml::encode($data["male"]));
							return "<div class='j'><font style='color:#FF6600; font-weight:bold;'>".CHtml::link( CHtml::encode($data["male"]),
							Yii::app()->createUrl( "site/editGermplasm", array("germplasm"=>$data["male"],"error"=>$data["mremarks"])),array('title' => CHtml::encode($data["mremarks"]), 'class'=>'tooltipster'))."</font></div>";
						}
                                        echo CHtml::hiddenField('hiddenMid',CHtml::encode($data["male"]));        
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
 ?>

 </div>

 <div class="assign">
	
	       <?php 
			$url = Yii::app()->createUrl('site/assignGID');
			$this->widget('bootstrap.widgets.TbButton', array(
						'type'=>'primary',
                        'label'=>'AssignGID',
                        //'url' =>array('site/assignGID'),
                        'htmlOptions' => array(
							'onclick' => 'js:
							
								var selected = $("#pedigreeGrid").selGridView("getAllSelection");
								$("#germplasm-id").val(selected);
								$("#submit-btn").click();
								/*var selected = $("#pedigreeGrid").selGridView("getAllSelection"); 
								alert(selected);
								$.ajax({
									type: "POST",
									data: {selectedIds:selected},
									url: "'. $url .'",
									success: function(response){
										//window.location="'.$url.'";
									}
								});*/
							',
                        ),
                )); 
               echo CHtml::textField('Germplasm[gid]','',array(
					'id' => 'germplasm-id',
					'form' => 'assign-gid-form',
					'class' => 'hidden',
                 ));
					echo CHtml::submitButton('Submit', array(
						'id' => 'submit-btn',
						'class' => 'hidden',
						'form' => 'assign-gid-form',
						//'onclick' => 'js: alert("hello");',
					));
					
					$this->endWidget();
                ?>

</div>
<div id="eventlist"></div>
<?php //$this->endWidget();?>
<script type="text/javascript">
$(document).ready(function() {

 //triggers  the activity loading indicator
 var pop = function(){
        var selected = $.fn.yiiGridView.getSelection("pedigreeGrid"); 
		if ( ! selected.length)
			{
				alert('Please select atleast one germplasm');
				return false;
            }
		else
		{	
			$('#screen').css({ opacity: 0.4, 'width':$(document).width(),'height':$(document).height()});
			$('body').css({'overflow':'hidden'});
			$('#ajax-loading-indicator').css({'display': 'block'});
		}
 }
 $('#submit-btn').click(pop);
 
 //checkboxes
// $("#ajaxSubmit").click(function(){
        //$(".selection").show();
		//alert( $.fn.yiiGridView.getSelection("pedigreeGrid"));
		//var arr = $("#pedigreeGrid").selGridView("getAllSelection");
		//alert(arr);
		
 //});
 /* function selectAll(){
    var inputs = document.getElementsByTagName("input");
    var checkboxes = [];
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].type == "checkbox") {
            inputs[i].checked = true;
        }
    }
  }*/
});
</script>
