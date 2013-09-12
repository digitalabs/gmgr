<?php if($uploaded):?>
<p>File was uploaded. Check <?php echo $dir?>.</p>
<?php endif ?>
<?php /*echo CHtml::beginForm('','post',array 
   ('enctype'=>'multipart/form-data'))*/?>
   <?php //echo CHtml::error($model, 'file')?>
   <?php //echo CHtml::activeFileField($model, 'file')?>
   <?php //echo CHtml::submitButton('Upload')?>
<?php // echo CHtml::endForm()?>
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
    'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	   ),
    'htmlOptions' => array('class'=>'well','enctype' => 'multipart/form-data'),
)); ?>


<div class="row">

  <div class="span11">
    <div class="row">
     <!--<form action="../../modules/getFile.php" method="POST" enctype="multipart/form-data">-->
        <div class="span6">
           <fieldset>
              <legend>Upload File</legend>
                <br>
                <!--<table width="100%">-->
					
					
                    <!--<tr><td> Load Sample File:&nbsp;</td><td><b>germplasmList.csv</b><br>&nbsp;</td></tr>-->
                    <!--<tr><td>--><?php
                        //$this->widget('bootstrap.widgets.TbFileUpload',array($model,'file'));
                         echo CHtml::error($model, 'file');
                         echo CHtml::activeFileField($model, 'file');?>
                    <!--</td></tr>
                    <tr>
                        <td>Location</td>
                        <td>-->
                        <label>Location</label>
                        <?php 
                              
                                $myfile =dirname(__FILE__).'/../../modules/location.csv';
                               
                             
                                $fin = fopen($myfile, 'r');
                               
                                echo '<select name="location" style="width:490px;" >';
                                
                                while ($line = fgetcsv($fin, 0, "#")) {
									if(count($line) !=3)
									{
										print_r($line);
									}
                                    echo '<option name="location[]" value="' . $line[0] . '">' . $line[2] . ': '.$line[1].'</option>';
                                }
                                fclose($fin);
                                echo "</select></br>"; 
                            ?>
                    <!--    </td>
                    </tr>
                </table>-->
               <!-- <div class="form-actions">-->
                  
                    <?php 
                       echo "</br>";
                        $this->widget('bootstrap.widgets.TbButton',array('buttonType'=>'submit','type'=>'primary','label'=>'Upload list')); 
                    ?>
                <!--</div>-->
           </fieldset><br>
        </div>
       <!-- <div class="divider"></div>-->
        <div class="span4">
                 <fieldset>
                    <legend>Select List Type</legend>
                    <input type="radio" name="group1" value="BreedersCrossHistories" checked> Breeders Cross Histories &nbsp; &nbsp;<a href="Nomenclature Rules/NomenclatureRules.htm">Nomenclature Rules</a> <br>
                    
                    <input type="radio" name="group1" value="CultivarList" disabled="true"> Cultivar List<br>
                    <input type="radio" name="group1" value="Accession" disabled="true"> Accession
                    <?php 
                       //echo $form->radioButtonListRow($model,'rButtons',array ('Breeders Cross Histories','Cultivar List','Accession'));   
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
    <!--</form>-->
	</div>
 </div>
</div>
<!--<script src="bootstrap-fileupload.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
-->

<?php $this->endWidget(); ?>

