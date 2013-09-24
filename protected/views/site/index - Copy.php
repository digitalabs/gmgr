<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<table>
	<tr>
		<td width="30%">
			<div id="myCarousel" class="carousel">
			<?php $this->widget('bootstrap.widgets.TbCarousel', array(
			    'items'=>array(
			        array('image'=>'http://www.sjknaturals.com/images/annapoornna-rice.jpg', 'label'=>'Phenotypes of Germplasm', 'caption'=>'Sample Text'),
			        array('image'=>'http://files.gereports.com/wp-content/uploads/2012/07/RiceChaffs.jpg', 'label'=>'Genetic Pedigree', 'caption'=>'Sample Text'),
			        array('image'=>'http://www.genengnews.com/Media/images/Article/Nov12_2012_17616014_ResearcherAtComputer_BigData4716121215.jpg', 'label'=>'Hello World', 'caption'=>'Sample Text'),
			    ),
				'htmlOptions'=>array(),
			)); ?>
			</div>
		</td>
		<td width="70%">
		<div>
			<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
			    'heading'=>'Welcome',
			)); ?>
				
			    <p>This is the <kbd>IRRI Genealogy Manager</kbd>. A merge of the Pedigree tools [Importer and Editor].</p>
			    <p><?php $this->widget('bootstrap.widgets.TbButton', array(
			        'type'=>'warning',
			        'size'=>'large',
			        'label'=>'Learn more',
					//'url'=>array('/site/login'), 
					//'visible'=>Yii::app()->user->isGuest),
					'htmlOptions'=>array('class'=>'btn-large'),
					//'active'=>!Yii::app()->user->isGuest,
					//'disabled'=>!Yii::app()->user->isGuest,
			    )); ?></p>
				
			<?php $this->endWidget(); ?>
		</td>
		</div>
	</tr>
</table>