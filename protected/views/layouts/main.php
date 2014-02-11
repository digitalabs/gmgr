<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	
    
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<?php echo Yii::app()->bootstrap->init();?>
</head>

<body>


<?php
  /*$flashMessages = Yii::app()->user->getFlashes();
		if ($flashMessages) {
			echo '<ul class="flashes">';
			foreach($flashMessages as $key => $message) {
				echo '<li><div class="flash-' . $key . '">' . $message . "</div></li>\n";
			}
			echo '</ul>';
		}*/
	$this->widget('ext.PNotify.PNotify',
          array(
              'flash_messages_only' => true,
          )
  );
?>

   <?php $this->widget('ext.tooltipster.tooltipster',
			array(
            'options'=>array('position'=>'right',
			'animation'=>'fade',
			'arrow'=>true,
			'arrowColor'=>'',
			'content'=>'',
			'delay'=>'200',
			'fixedWidth'=>'300',
			'functionBefore'=>'js:function(origin, continueTooltip) { continueTooltip(); }',
			'functionAfter'=>'js:function(origin) {}',
			'icon'=>'(?)',
			'iconTheme'=>'.tooltipster-icon',
			'iconDesktop'=>false,
			'iconTouch'=>false,
			'interactive'=>false,
			'interactiveTolerance'=>'350',
			'offsetX'=>'5',
			'offsetY'=>'5',
			'onlyOne'=>true,
			'position'=>'top',
			'speed'=>'350',
			'timer'=>'0',
			'theme'=>'.tooltipster-default',
			'touchDevices'=>true,
			'trigger'=>'hover'
			)
         ));
      
   ?>
	<div id="mainmenu">
	
	<?php $this->widget('bootstrap.widgets.TbNavbar', array(
    'type'=>'inverse', // null or 'inverse'
    'brand'=>'Genealogy Manager',
    'brandUrl'=>'#',
    'collapse'=>true, // requires bootstrap-responsive.css
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>'Home', 'url'=>array('/site/index')),
				//array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				//array('label'=>'Contact', 'url'=>array('/site/contact')),
				//array('htmlOptions'=>array('class'=>'pull-right'),'label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Pedigree Importer', 'url'=>array('/site/importer'),'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Pedigree Editor ', 'url'=>array('/site/editor'), 'visible'=>!Yii::app()->user->isGuest)
               
            ),
        ),
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'htmlOptions'=>array('class'=>'pull-right'),
            'items'=>array(
                array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
                //array('htmlOptions'=>array('class'=>'pull-right'),'label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
            ),
        ),
    ),
)); ?>
<!--
		<p class="nav navbar-text">GM</p>
-->
		 <?php //$this->widget('zii.widgets.CMenu',array(
			//'items'=>array( 'htmlOptions'=>array('class'=>'pull-right'),
				//array('label'=>'Home', 'url'=>array('/site/index')),
				////array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				////array('label'=>'Contact', 'url'=>array('/site/contact')),
				//array('htmlOptions'=>array('class'=>'pull-right'),'label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				//array('label'=>'Pedigree Importer', 'url'=>array('/site/importer'), 'visible'=>!Yii::app()->user->isGuest),
				//array('label'=>'Pedigree Editor', 'url'=>array('/site/editor'), 'visible'=>!Yii::app()->user->isGuest),
				//array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			//),
		//)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<!--<div id="footer">
		<!--Copyright &copy; <?php //echo date('Y'); ?> by My Company.<br/>
		All Rights Reserved.<br/>
		<?php //echo Yii::powered(); ?>
		<?php //$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
		    //'links'=>array('About Us'=>'#', 'Contact Us'=>'#'),
		//)); ?>
	</div>--><!-- footer -->




<!-- page -->

</body>
</html>
