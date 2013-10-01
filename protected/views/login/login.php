<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */
/*
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);*/
?>
<body>
<div class="container">


<!--<p>Please fill out the following form with your login credentials:</p>-->

<div class="form">
<?php /** @var BootActiveForm $form */ 
     $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'login-form',
	'type'=>'horizontal',
	'htmlOptions'=>array('class'=>'well'),
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
    <h1>Please Sign In</h1>
	<p class="note">Fields with <span class="required">*</span> are required.</p>
    
	<div class="row">
	    <?php $model->username='GUEST';?>
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
		
	</div>

	<div class="row">

	    <?php $model->password='GUEST';?>
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>

		<!--<p class="hint">
			Hint: You may login with <kbd>demo</kbd>/<kbd>demo</kbd> or <kbd>admin</kbd>/<kbd>admin</kbd>.
		</p>-->
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row buttons">
		<?php 
		   //echo CHtml::submitButton('Login'); 
		   $this->widget('bootstrap.widgets.TbButton',array('buttonType'=>'submit','label'=>'Login'));
		?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
</div>
</body>