
<?php
	Yii::import('application.modules.configDB');
        Yii::import('application.modules.file_toArray');
        Yii::import('application.modules.curl');

 $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'editGermplasm',
	'type'=>'horizontal',
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	   ),
    'htmlOptions' => array('class'=>'well','enctype' => 'multipart/form-data'),
)); ?>
 <fieldset>
	 <legend>Edit Germplasm Name</legend>
      
	 <?php $model->germplasmName=$_GET['germplasm'];?>
	 <?php echo $form->textFieldRow($model,'germplasmName',array('hint'=>$_GET['error'])); ?>
	

	 <?php echo $form->textFieldRow($model,'newGermplasmName')?>
         
        <?php //CHtml::hiddenField('error', $_GET['error']); ?>
	
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'type'=>'primary', 
            'label'=>'Save Changes',
            'buttonType'=>'submit',
            'url'=>array('/site/savegermplasm'),
         )); ?>
       
</fieldset>	 
<?php $this->endWidget(); ?>
