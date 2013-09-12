<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<table>
	<tr>
		<td>
			<div id="myCarousel" class="carousel"><br><br><br>
			<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
				'heading'=>'IRRI GMgr',
			)); ?>
			 
				<p><font size="3" face="verdana" color="#FF8000">This is the IRRI Genealogy Manager. A merge of the Pedigree tools <br>[Importer and Editor].</font></p>
				
			 
			<?php $this->endWidget(); ?>
			</div>
		</td>
	</tr>
</table>
