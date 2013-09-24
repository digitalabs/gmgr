<?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'stnTable',
            'enableAjaxValidation'=>false,
        )); ?>
 
 <div id='table1'>
 <?php
 $this->widget('ext.selgridview.BootSelGridView', array(
     'id' => 'pedigreeGrid',
	 'dataProvider'=> $dataProvider,
	 'filter'=>$filtersForm,
	 'selectableRows' => 2,
     'columns'=>array(
                array(
                    'class'=>'CCheckBoxColumn',   
                    ),
                array(
                    'header'=>'Cross Name', 
                    'value'=>'CHtml::encode($data["nval"])',
                    'name'=>'',
                    //'filter'=>CHtml::textField('FilterPedigreeForm[nval]'),
                    //'filter'=>CHtml::textField('FilterPedigreeForm[nval]',isset($_GET['FilterPedigreeForm']['nval]'])? $_GET['FilterPedigreeForm']['nval']:''),
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
                  // 'filter'=>CHtml::textField('FilterPedigreeForm[gid]',isset($_GET['FilterPedigreeForm']['gid]'])? $_GET['FilterPedigreeForm']['gid']:''),
                    ),
               array(
                   'header'=>'Female Parent',
                   'type' =>'raw',
                   'value'=>'CHtml::link( CHtml::encode($data["female"]),
                       Yii::app()->createUrl( "site/editGermplasm", array("germplasm"=>$data["female"]) ))',//,"error"=>$data["fremarks"]
                   //'value' => 'CHtml::link(CHtml::encode($data["female"]), array("site/showGID","germplasm"=>"CHtml::encode($data["female"])"))',

                   //'value'=>'CHtml::encode($data["female"])',
                   'name'=>'female',
                   //'filter'=>CHtml::textField('FilterPedigreeForm[female]'),
                   // 'filter'=>CHtml::textField('FilterPedigreeForm[female]',isset($_GET['FilterPedigreeForm']['female]'])? $_GET['FilterPedigreeForm']['female']:''),
               ),     
               array(
                   'header'=>'Male Parent',
                   'value'=>'CHtml::encode($data["male"])',
                   'name'=>'male',
                   //'filter'=>CHtml::textField('FilterPedigreeForm[male]'),
                  //  'filter'=>CHtml::textField('FilterPedigreeForm[male]',isset($_GET['FilterPedigreeForm']['male]'])? $_GET['FilterPedigreeForm']['male']:''),
               ),
               array(
                    'header'=>'New GID',
                    'name' =>'mgid',
                    'value'=>'CHtml::encode($data["mgid"])',
                    //'filter' => CHtml::textField('F1[tvp]',isset($_GET['F1']['tvp']) ? $_GET['F1']['tvp'] : ''),
                 //  'filter'=>CHtml::textField('FilterPedigreeForm[mgid]',isset($_GET['FilterPedigreeForm']['mgid]'])? $_GET['FilterPedigreeForm']['mgid']:''),
               ),
      ),
));
 ?>
 </div>
 <?php
 //assign GID button
 $this->widget('bootstrap.widgets.TbButton',array(
    'id' =>'assignGID',
    'label' => 'Assign GID',
    'type' => 'primary',
    // 'url'=> array('/site/showGID'),
     ));
?> 
<div id="selected-keys">Selected:

</div>
<?php $this->endWidget();?>

<script>
	$(document).ready(function(){
		$("#assignGID").bind('click', function(){
		    var selected = $.fn.yiiGridView.getSelection("pedigreeGrid"); 
		     alert(selected.fid);
			 $("#selected-keys").html("Selected: [" + selected.join(", ") + "]");	
			 //$("#selected-keys").html("Selected: " + selected.length);
			 
			 //if nothing's selected
			if ( ! selected.length)
			{
				alert('Please select minimum one item to be deleted');
				return false;
            }

	     });
	});
</script>
