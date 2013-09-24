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


       <!--       <h3 style=" border-bottom: 0px solid #999; color:#666">Created GID for cross <font style="color:#e13300; "> <?php //echo $pages[0][count($pages[0]) - 1][2]; ?></font></h3>
              <p><small><i><strong>Note:</strong>&nbsp; 
                                          Germplasm names with <b>multiple GID's</b> display a <b>CHOOSE GID</b> link and the <b>preceeding pedigrees</b> display <b>NOT SET</b>.
                                                When a GID is chosen, the GID(s) of the preceeding pedigree(s) will also be set. 
                                                When <b>NOT SET</b> is still displayed,the GID of that pedigree on a location <b>does not exist</b>.
                        </i>
              </small></p>-->
              <?php
            
                  $this->widget('ext.selgridview.BootSelGridView',  array(
                        'id' => 'createdGID',
                        'dataProvider'=>$dataProvider,
                        'template'=>'{summary}{items}{pager}',
                        'enablePagination' => true,
                        'htmlOptions'=>array('class'=>'createdGID'),
                        'cssFile' => Yii::app()->baseUrl . '/css/gridViewStyle/gridView.css',
 
                         'columns'=>  array(
                             array(
                                 'header'=>'Germplasm Name',
                                 'type'=>'raw',
                                 'value'=> 'CHtml::encode($data["term"])',/*,function($data){                
                                          
                                          if(strcmp(CHtml::encode($data["id"]),$fgid)==0){
                                               return "<font style='color:#FF6600;'>".CHtml::encode($data["term"])."</font>";
                                          } else{
                                              return CHtml::encode($data["term"]);
                                          }
                                         
                                 },*/
                                  'htmlOptions'=>array(
                                     'style'=>'width:50px;',
                                     'title'=>'tooltip sample'
                                     ),
                             ),
                             array(
                                 'header'=>'GID',
                                 'type'=>'raw',
                                 'value'=>function($data){
                                          if(CHtml::encode($data["GID"])=== "CHOOSE GID"){
                                                    $m_term = CHtml::encode($data["term"]);
                                                   /* $m_id = $id;
                                                    $m_pedigree = $nval;
                                                    $m_nval = $nval;
                                                    $m_mid = $mid;
                                                    $m_fid = $fid;
                                                    $m_female = $female;
                                                    $m_male = $male;*/
                                               //'$("#juiDialog").dialog("open");return false;'
                                                 /*   return CHtml::link('Choose GID', '#',array(
                                                        'onclick'=> CHtml::ajax(array(
                                                                'type'=>'post',
                                                                'url'=>array('/site/showFixedLine'),
                                                                'data'=>array('id'=>$data->term),
                                                                'success'=>'js:function(response){
                                                                        $("#createdGID").selGridView("clearAllSelection");
                                                                    }'  
                                                         )),
                                                    ));*/
                                                    return CHtml::link('Choose GID');
                                          }else if( CHtml::encode($data["GID"])==="DUPLICATE" || CHtml::encode($data["GID"])==="NOT SET"){
                                              return "<font style='font-weight:bold;'>".CHtml::encode($data["GID"])."</font>";
                                          }else{
                                              return CHtml::encode($data["GID"]);
                                          }
                                 },
                             ),
                             array(
                                 'header' => 'Method',
                                 'value'=>function($data){
                                        return "(".CHtml::encode($data["methodID"]).") ".CHtml::encode($data["method"]);
                                 }
                                 
                             ),
                             array(
                                 'header'=>'Location',
                                 'value'=>function($data){
                                       return "(".CHtml::encode($data["locID"]).") ".CHtml::encode($data["location"]);
                                 }
                             ),        
                                    
                         ),
                  ));
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
                                                    return '<a data-toggle="tooltip" title="' .CHtml::encode($data["mremarks"]) . '" data-placement="right" style="color:rgb(255, 0, 0); font-weight:bold;" href="/PedigreeImport/editGermplasm.php?germplasm=' .CHtml::encode($data["female"]) . '&error=' .CHtml::encode($data["fremarks"]). '">' . CHtml::encode($data["female"]) . '<a>';
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
                                                    echo '<a data-toggle="tooltip" data-placement="right" title="' . $mremarks . '" style="color:rgb(255, 0, 0); font-weight:bold;" href="/PedigreeImport/editGermplasm.php?germplasm=' . CHtml::encode($data["male"]) . '&error=' . $mremarks . '">' . CHtml::encode($data["male"]) . '<a>';
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