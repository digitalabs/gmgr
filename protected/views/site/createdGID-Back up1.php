<?php
    $this->widget('ext.PNotify.PNotify', array(
        /*'options'=>  array(
               'title'=>'You did it',
               'text'=>'You are awesome!',
               'type'=>'success',
               'closer'=>false,
               'hide'=>false
        )*/
         'flash_messages_only'=> true,
    ));
    
   Yii::app()->user->setFlash('success', array('title' => 'Login Successful!', 'text' => 'You successfully logged in. Enjoy!'));
?>

<?php /*$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'createdGID',
    'type'=>'horizontal',
    'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	   ),
    'htmlOptions' => array('class'=>'well','enctype' => 'multipart/form-data'),
        )); */
 ?>
<?php
 /*   Yii::import('application.modules.file_toArray');
     $file_toArray = new file_toArray();
     $checked = $file_toArray->json_checked();
      
     // final is the array containing arrays of the pedigree lines (from the checkedboxes)
     Yii::import('application.modules.file_toArray');
     $final = $file_toArray->getPedigreeLine();    
     //echo "final count:".count($final);
     // If we have an array with items
if (count($final)) {
 
     Yii::import('application.modules.pagination');
    $pagination = new pagination($final, (isset($_GET['pagea']) ? $_GET['pagea'] : 1), 1);
    // Decide if the first and last links should show
    $pagination->setShowFirstAndLast(false);
    /* You can overwrite the default seperator
    $pagination->setMainSeperator(' | ') 
     * Parse through the pagination class*/
   // $pages = $pagination->getResults();
   // echo "pages:".count($pages);
   //print_r($pages);
   // If we have items 
 //   if (count($pages) != 0) {
  
?>


    
              <?php
                                    // print out the page numbers beneath the results
                                   /* $pageNumbers = $pagination->getLinks2($_GET, $count_tobe_processed);
                                    echo " <div class='panel-footer'>";
                                    echo "<ul class='pager'>";
                                    echo $pageNumbers;
                                    echo '</ul>';
                                    echo "</div>"*/
               ?>
           
 <?php
//}}
 ?>
            <div id="chooseGIDList">
                 <?php
                    include( dirname(__FILE__). "/assignGID.php");
                ?>
            </div>
                     <?php
                        $this->widget('ext.selgridview.BootSelGridView', array(
                                 'id' => 'germplasmList',   
                                 'dataProvider' => $GdataProvider,
                                 'columns'=> array(
                                     array(
                                       'header'=>'Cross Name',
                                       'value'=>'CHtml::encode($data["nval"])',
                                     ), 
                                     array(
                                         'header'=>'GID',
                                         'value'=>'CHtml::encode($data["gid"])',
                                     ),
                                     array(
                                         'header'=>'Female Parent',
                                         'type'=>'raw',
                                         'value'=>function($data){
                                               $line = array();
                                               $line = explode("#", CHtml::encode($data["fremarks"]));
                                               $line = implode("\n", $line);
                                               $fremarks = $line;
                                               if (strcmp($fremarks, 'in standardized format') == 0) {
                                                
                                                    return '<a style="color:black;" data-toggle="tooltip" data-placement="right" title="' .$fremarks. '">'.CHtml::encode($data["female"]).'</a>';
                                                } else {
                                                  //  return "<div class='j'><font style='color:#FF6600; font-weight:bold;'>".CHtml::link( CHtml::encode($data["female"]),
                                                     //       Yii::app()->createUrl("site/editGermplasm", array("germplasm"=>$data["female"],"error"=>$data["fremarks"]) ))."</font></div>";
                                                    return '<a data-toggle="tooltip" title="' .CHtml::encode($data["mremarks"]) . '" data-placement="right" style="color:rgb(255, 0, 0); font-weight:bold;" href="/editGermplasm.php?germplasm=' .CHtml::encode($data["female"]) . '&error=' .CHtml::encode($data["fremarks"]). '">' . CHtml::encode($data["female"]) . '<a>';
                                                }
                                         },
                                     ),
                                     array(
                                         'header'=>'Male Parent',
                                         'type'=>'raw',
                                         'value'=>function($data){
                                              $line = array();
                                              $line = explode("#", CHtml::encode($data["mremarks"]));
                                              $line = implode("\n", $line);
                                              $mremarks = $line;
                                               if (strcmp($mremarks, 'in standardized format') == 0) {
                                                    return '<a data-toggle="tooltip" data-placement="right" title="' . $mremarks . '">' . CHtml::encode($data["male"]) . '</a>';
                                                } else {
                                                    echo '<a data-toggle="tooltip" data-placement="right" title="' . $mremarks . '" style="color:rgb(255, 0, 0); font-weight:bold;" href="/site/editGermplasm.php?germplasm=' . CHtml::encode($data["male"]) . '&error=' . $mremarks . '">' . CHtml::encode($data["male"]) . '<a>';
                                                }
                                         },
                                     ),
                                     array(
                                         'header'=>'New GID',
                                         'type'=> 'raw',
                                         'value'=>function($data){
                                               $line = array();
                                               $line = explode("#", CHtml::encode($data["fgid"]));
                                               $line = implode("\n", $line);
                                               $fgid = $line;
                                                

                                               $line = array();
                                               $line = explode("#", CHtml::encode($data["mgid"]));
                                               $line = implode("\n", $line);
                                               $mgid = $line;
                                              
                                               return  '<pre>' . $fgid . '</pre><pre>' . $mgid . '</pre>';
                                         },
                                     ),
                                 ),
                         ));
                     ?>
 
<?php //$this->endWidget();?>


              <?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'juiDialog',
                'options'=>array(
                    'title'=>'Show data',
                    'autoOpen'=>false,
                    'modal'=>true,
                    'width'=>'auto',
                    'height'=>'auto',
                ),
                ));
$this->endWidget();
?>