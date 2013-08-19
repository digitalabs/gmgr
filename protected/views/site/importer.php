<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Importer';
$this->breadcrumbs=array(
	'Importer',
);
?>
<?php
/* @var $this SiteController */

//include_once($_SERVER['DOCUMENT_ROOT'] . "/PedigreeImport/model/configDB.php");

?>
<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'pedigreeImport',
    'type'=>'horizontal',
)); ?>

<!--<div class="breadcrumb">
  <span>
 <?php if(isset($this->breadcrumbs)):?> 
<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
       'links'=>array('Pedigree Import')
       ));
?>
<?php endif?>
  </span>
</div>-->

<div class="row">

  <div class="span11">
    <div class="row">
     <form action="getFile.php" method="POST" enctype="multipart/form-data">
        <div class="span6">
           <fieldset>
              <legend>Upload File</legend>
                <br>
                <table width="100%">
                    <tr><td> Load Sample File:<br>&nbsp;</td><td><b>germplasmList.csv</b><br>&nbsp;</td></tr>
                    <tr>
                        <td>Location</td>
                        <td><?php 
                                //Yii::setPathOfAlias('local','path/to/local-folder');
                                $myfile =Yii::setPathOfAlias('local',dirname(__FILE__).'/../files/location.csv');
                                echo $myfile;
                                $fin = $this->fopen($myfile, 'r');
                                $line = fgetcsv($fin, 10000);

                                echo '<select name="location" style="width:490px;" >';
                                while ($line = fgetcsv($fin, 0, "#")) {
                                    echo '<option name="location[]" value="' . $line[0] . '">' . $line[2] . ': '.$line[1].'</option>';
                                }
                                fclose($fin);
                                echo "</select>"; 
                                ?></td>
                    </tr>
                </table>
               <!-- <div class="form-actions">-->
                    <?php $this->widget('bootstrap.widgets.TbButton',array('buttonType'=>'submit','type'=>'primary','label'=>'Upload list'));?>
                <!--</div>-->
           </fieldset><br>
        </div>
       <!-- <div class="divider"></div>-->
        <div class="span4">
                 <fieldset>
                    <legend>Select List Type</legend>
                    <!--<input type="radio" name="group1" value="BreedersCrossHistories" checked> Breeders Cross Histories &nbsp; &nbsp;<a href="/PedigreeImport/NomenclatureRules.htm">Nomenclature Rules</a> <br>
                    
                    <input type="radio" name="group1" value="CultivarList" disabled="true"> Cultivar List<br>
                    <input type="radio" name="group1" value="Accession" disabled="true"> Accession-->
                    <?php //echo $form->radioButtonListRow($model,'radioButtons',array ('Breeders Cross Histories','Cultivar List','Accession'));
					?>
		
                    <br>
                    <br>
                    <br>
                </fieldset><br>
                <fieldset>
                    <legend>Options</legend>
                    <input type="radio" name="group2" value="singleHit" checked> Accept single hit search<br>
                    <br>
                    <br>
                    <br>
                </fieldset><br>
        </div>
    </form>
	</div>
 </div>
</div>

<?php $this->endWidget(); ?>

