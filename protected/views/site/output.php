<div id="tableEvent"></div>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'standardTable',
    'type'=>'horizontal',
    'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	   ),
    'htmlOptions' => array('class'=>'well','enctype' => 'multipart/form-data'),
        )); 
 ?>
 
 <div id='table1'>
	     <h3>Germplasm List</h3>
    <i><p ><strong>Note:</strong>&nbsp; 
            Germplasm names not in standardized format are in red color.Hover the mouse over the germplasm names to see the error and click to correct it.
        </p></i>
 <?php
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
                        'title'=>'tooltip sample'
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
                       Yii::app()->createUrl( "site/editGermplasm", array("germplasm"=>$data["female"],"error"=>$data["fremarks"]) ))."</font></div>";
                                                        
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
                       Yii::app()->createUrl( "site/editGermplasm", array("germplasm"=>$data["male"],"error"=>$data["mremarks"]) ))."</font></div>";
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
<?php $url = Yii::app()->createUrl('site/createdGID'); ?>
<?php echo CHtml::ajaxSubmitButton('AssignGID', Yii::app()->createUrl("site/createdGID"),
     array(
            'type'=>'POST',
            'update'=>'#table1',
            //'data'=>'js{selectedIds: $.fn.yiiGridView.getSelection("pedigreeGrid")}',
           // 'data'=>'js:jQuery(thid).parents("form").serialize()+"&isAjaxREquest=1"',
            'success'=>'function(html){$("#table1").replaceWith(html); $("#ajaxSubmit").hide();
                   window.location="'.$url.'";
                  }'
        ),
     array(//'update'=>'#table1'
         'id'=>'ajaxSubmit',
         'name'=>'ajaxSubmit'
     )  
     );
 ?>
</div>

</div>
<?php $this->endWidget();?>

