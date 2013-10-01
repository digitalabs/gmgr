<?php
Yii::import('application.modules.file_toArray');
Yii::import('application.modules.json');
Yii::import('application.modules.curl');
Yii::import('application.modules.pagination');
Yii::import('application.modules.configDB');
Yii::import('application.modules.model');

$model = new model();

$file_toArray = new file_toArray();

$unselected=0;
if (isset($_GET['yes'])) {
    Yii::import('application.modules.file_toArray');
    $unselected = $file_toArray->get_unselected_rows();
    $standardized = $file_toArray->checkIf_standardize($unselected);
    //echo "standardize unselected:";
    //print_r($standardized);
    Yii::import('application.modules.json');
    $json = new json($standardized);
    $json->checkedBox();
    
    //call curl: function createdGID
    Yii::import('application.modules.curl');
    $curl = new curl();
    $curl->createGID();

    Yii::import('application.modules.model');
    $url = $model->curPageURL();
    $values = parse_url($url);
   
    $query = explode('&', $values['query']);

    for ($i = 0; $i < count($query); $i++) {
        if ('yes=1' != $query[$i]) {
            $append[] = $query[$i];
        }
    }
    $query = implode('&', $append);
    $values['query'] = $query;
    $url = $values['scheme'] . '://' . $values['host'] . '/' . $values['path'] . '?' . $values['query'];

    header("Location: " . $values['path'] . "?" . $values['query'] . "");
}

if (isset($_POST['checked'])) {
    $checked = $_POST['checked'];
    $cross = $_POST['cross'];
    //echo $cross;
    $fid = $_POST['fid'];
    $mid = $_POST['mid'];

    $standardized = $file_toArray->checkIf_standardize($checked);

//json fil['e of checked boxes
    $json = new json($standardized);
    $json->checkedBox();

//call curl: function createdGID
    $curl = new curl();
    $curl->createGID();

// update createdGID.csv
    $file_toArray = new file_toArray();
    $file_toArray->update_csv_correctedGID($fid, $mid, $checked);
}

 if (isset($_POST['choose'])) {
    echo "choose:".$_POST['choose'];
    $term = strip_tags($_POST['term']);
    $pedigree = strip_tags($_POST['pedigree']);
    $id = strip_tags($_POST['id']);
    $choose = strip_tags($_POST['choose']);
    $fid = strip_tags($_POST['fid']);
    $mid = strip_tags($_POST['mid']);
    $female = strip_tags($_POST['female']);
    $male = strip_tags($_POST['male']);

	//json file of chosen GID among existing germplasm names
    $json = new json($_POST['choose']);
    $json->checkedBox();

	//update the createdGID.csv with the chosen GID among the existing germplasm names
    $data = $file_toArray->updateGID_createdGID($term, $pedigree, $id, $choose, $fid, $mid, $female, $male);

	//json file of the details of the chosen GID among existing terms
    $json = new json($data);
    $json->chosenGID();

	//call curl: function chooseGID
    $curl = new curl();
    $curl->chooseGID();
    
    //open and store checked boxes
     $myfile = dirname(__FILE__).'/../../modules/checked.json';
            
            $fp = fopen($myfile, 'r');
            $rows = array();
            while(($row = fgetcsv($fp)) !== FALSE){
                $rows[] = $row;
            }
            fclose($fp);
          // echo "rows:";
         
          $checked = $rows;
    
	// update createdGID.csv
    $file_toArray->update_csv_correctedGID($fid, $mid, $checked);
}

$file_toArray = new file_toArray();
$checked = $file_toArray->json_checked();


// final is the array containing arrays of the pedigree lines (from the checkedboxes)
$final = $file_toArray->getPedigreeLine();

// If we have an array with items
if (count($final)) {
// Create the pagination object
    $pagination = new pagination($final, (isset($_GET['pagea']) ? $_GET['pagea'] : 1), 1);
// Decide if the first and last links should show
    $pagination->setShowFirstAndLast(false);
// You can overwrite the default seperator
// $pagination->setMainSeperator(' | ');
// Parse through the pagination class
    $pages = $pagination->getResults();

// If we have items 
    if (count($pages) != 0) {
// Create the page numbers
// Loop through all the items in the array

        $count = 0;
// echo "count: " . count($pages);
        Yii::import('application.modules.file_toArray');
        $checked = $file_toArray->csv_checked2();

        foreach ($pages[0] as $r) : list($id, $nval, $term, $GID, $methodID, $method, $locID, $location) = $r;
            for ($j = 0; $j < count($checked); $j++) {
                $a = $checked[$j] + 1;

                if ($checked[$j] === $id) {
                    $female = $nval;
                    $fid = $id;
                }
                if ($a === (int) $id) {
                    $male = $nval;
                    $mid = $id;
                }
            }
        endforeach;

       $var4 = (count($checked) - 1);
        //echo (count($checked) - 1) . " rows selected<br>";
        $tobe_processed = $file_toArray->csv_corrected();
		//echo "<br><br><br><br><br><br><br><br><br><br>".count($tobe_processed);
        $count_tobe_processed = (count($tobe_processed) - (count($checked) - 1));
        //echo $count_tobe_processed . " remaining rows to be processed<br>";
        $var1 = $count_tobe_processed;

		Yii::import('application.modules.file_toArray');
        $unselected = $file_toArray->get_unselected_rows();
        $unstandardized = $file_toArray->checkIf_standardize($checked);
       
       // echo "<br><br>" . count($unstandardized);
        //echo "<br><br>" . count($checked);
      //  echo (count($checked) - (count($unselected) - 1)) . " row(s) selected with unstandardized germplasm name(s)<br>";
        $var3 = (count($checked) - (count($unselected) - 1));
         
        Yii::import('application.modules.file_toArray'); 
        $GID_rows = $file_toArray->csv_corrected_GID();
        //echo "GID_rows:".count($GID_rows);
        $var2 = count($GID_rows);
        //echo count($GID_rows) . " created GID(s)<br>";
        ?>



        <body id="page" data-spy="scroll"  onload="show(<?php echo $var1; ?>,<?php echo $var2; ?>,<?php echo $var3; ?>,<?php echo $var4; ?>);">
           <link href="assets/bootstrap-responsive.css" rel="stylesheet" type="text/css">

            <link href="assets/pnotify-1.2.0/jquery.pnotify.default.css" rel="stylesheet" type="text/css">
            <link href="assets/pnotify-1.2.0/jquery.pnotify.default.icons.css" rel="stylesheet" type="text/css">

            <div class="container" >

                <div class="page-points">
                    <br>
                </div>


                <div id="sections">
              
                    <div id="data">
                            <div class="panel panel-default" style="width:90%; font-size: 12px;text-align: left;">
                                <div class="panel-heading">
                                   
								<h3 style=" border-bottom: 0px solid #999; color:#666">Created GID for cross <font style="color:#e13300; "> <?php echo $pages[0][count($pages[0]) - 1][2]; ?></font></h3>
                                 
                                </div>
                                <div class="panel-body">
								 <?php $male_id = $file_toArray->output_tree_json($pages);// get what ith element in the array is the male parent?>

									<div class="alert alert-block">
                                       <?php
                                       $female_id=$pages[0][0];
                                       $i=0;
                                       $male_id = $file_toArray->output_tree_json($pages); // get what ith element in the array is the male parent
                                   
                                           foreach ($pages[0] as $r) : list($id, $nval, $term, $GID, $methodID, $method, $locID, $location) = $r;
                                              
                                           if ($i==0) {
                                                   if ($GID === "CHOOSE GID") {
                                                       if ($i + 1 == $male_id) {
                                                           echo "The female parent <b>" . $term . "</b> has multiple matches in the database. Please click the link and choose a GID <br>";
                                                       } else {
                                                           echo "The female parent <b>" . $term . "</b> has multiple matches in the database. Please choose a GID to set the GID(s) of the preceeding pedigree(s)<br>";
                                                       }
                                                   } else {
                                                       if ($i + 1 == $male_id) {
                                                           echo "The female parent <b>" . $term . "</b> has a single hit search from the local or central database.<br>";
                                                       } else {
                                                           echo "The female parent <b>" . $term . "</b> has a single hit search from the local or central database. Sequentially, the preceeding line(s) is/are also set.<br>";
                                                       }
                                                   }
                                               } else if ($i == $male_id) {
                                                   if ($GID === "CHOOSE GID") {
                                                       if (($i + 1) ==  count($pages[0])-1) {
                                                           echo "The male parent <b>" . $term . "</b> has multiple matches in the database. Please click the link and choose a GID<br>";
                                                       } else {
                                                           echo "The male parent <b>" . $term . "</b> has multiple matches in the database. Please choose a GID to set the GID(s) of the preceeding pedigree(s)<br>";
                                                       }
                                                   } else {
                                                       if (($i + 1) ==  count($pages[0])-1) {
                                                           echo "The male parent <b>" . $term . "</b> has a single hit search from the local or central database.";
                                                       } else  {
                                                           echo "The male parent <b>" . $term . "</b> has a single hit search from the local or central database. Sequentially, the preceeding line(s) is/are also set.";
                                                       }
                                                   }
                                               }$i++;
                                           endforeach;
                                  
                                       ?>
                                   </div>
                                   
                                    <table class="table table-hover ">
                                        <tbody>
                                        <thead>
										<th width="15px"></th>	
                                        <th>Germplasm Name</th>
                                        <th>GID</th>
                                        <th>Method </th>
                                        <th>Location</th>

                                        </thead>

                                        <?php
                                        //Get and Store female ID's and male ID's as gender indicators
                                         $femIdArr = array(); $maleIdArr=array();
                                         
                                         for ($i = 0; $i < count($pages); $i++) {
                                            foreach ($pages[$i] as $r) : list($id, $nval, $term, $GID, $methodID, $method, $locID, $location) = $r;
                                            if(strpos($id,'/')){
													
													$data1 = explode("/",$id);
													$femIdArr[0] = $data1[0];
													$maleIdArr[0] = $data1[1];
                                                    
												}
                                            endforeach;
                                         }
                                         
                                         //End
                                        
										 $i = 0;
                                   
                                            foreach ($pages[0] as $r) : list($id, $nval, $term, $GID, $methodID, $method, $locID, $location) = $r;
//<<<<<<< HEAD
                                                echo '<tr>';
                                             	
												//condition 2
//=======
                                               if($id==$femIdArr[0] ){
											   echo '<tr bgcolor="#FFE4E1"> ';
                                               }else if($id==$femIdArr[0]){
											   echo '<tr bgcolor="#E6E6FA"> ';
											   }else{
											    echo '<tr bgcolor="#90EE90"> ';
											   }
                                               
//>>>>>>> d790bf99914e9261c3908ed688cb133622098874
												if($id==$femIdArr[0] ){ //female 
													 if ($i === 0) {
														 echo "<td><img src='images/glyphicons_247_female2.png'></td>";
														 echo "<td>". $term . "</td>";
													 }
													 else{
														 echo "<td  bgcolor='#FFE4E1'></td><td>". $term . "</td>";
													 }
                                                    
											    }else if($id==$maleIdArr[0]) //male
											    {
													if ($i === $male_id) {
													  echo "<td><img src='images/glyphicons_246_male2.png'></td>";
													  echo "<td>". $term . "</td>";
												    }else{
													  echo "<td></td><td>". $term . "</td>";
													}  
												}else if(strpos($id,'/')){ //crossed
													  echo "<tdimg src='images/glyphicons_197_remove2.png'></td>";
													  echo "<td bgcolor='#90EE90'>" . $term . "</td>";
												}
												 $i++;
                                                if ($GID === "CHOOSE GID") {
														echo "<td><a  data-toggle='modal' href='#form-content' >Choose GID</a></td>";
                                                   
													    $m_term = $term;
														$m_id = $id;
														$m_pedigree = $nval;
														$m_nval = $nval;
														$m_mid = $mid;
														$m_fid = $fid;
														$m_female = $female;
														$m_male = $male; 
														
												} else if ($GID === "DUPLICATE" || $GID === "NOT SET") {
													
														  echo "<td><b><i>" . $GID . "</i></b></td>";
													 
												     
                                                } else {
													
														 echo "<td>" . $GID . "</td>";
													
                                                }
                                                //Methods
                                                
													  echo "<td>(" . $methodID . ")&nbsp; " . $method . "</td>";
												 
                                              
                                                //locations
                                          
													  echo "<td>(" . $locID . ")&nbsp; " . $location . "</td>";
												
                                               
                                                echo '</tr>';

                                            endforeach;
                                       // }
                                      
                                        ?>
                                        </tbody>
                                    </table>

                                    <?php
                                    // print out the page numbers beneath the results
                                    //print_r($_GET);
                                   // print_r($count_tobe_processed);
                                    $pageNumbers = $pagination->getLinks2($_GET, $count_tobe_processed);
                                    echo " <div class='panel-footer'>";
                                    echo "<ul class='pager'>";
                                    echo $pageNumbers;
                                    echo '</ul>';
                                    echo "</div>";
                                }
                            }
                         
                            ?>
                        </div>

                    </div>	
            
            </div>
                 
        </div
    </div>
           
    <style type="text/css">
        .well { background: #fff; text-align: center; }
        .modal-backdrop{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1040;background-color:#000000;}.modal-backdrop.fade{opacity:0;}
        .modal-backdrop,.modal-backdrop.fade.in{opacity:0.5;filter:alpha(opacity=80);}
        .modal{position:fixed;top:10%;left:40%;z-index:1050;width:900px;margin-left:-280px;background-color:#ffffff;border:1px solid #999;border:1px solid rgba(0, 0, 0, 0.3);*border:1px solid #999;-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px;-webkit-box-shadow:0 3px 7px rgba(0, 0, 0, 0.3);-moz-box-shadow:0 3px 7px rgba(0, 0, 0, 0.3);box-shadow:0 3px 7px rgba(0, 0, 0, 0.3);-webkit-background-clip:padding-box;-moz-background-clip:padding-box;background-clip:padding-box;outline:none;}.modal.fade{-webkit-transition:opacity .3s linear, top .3s ease-out;-moz-transition:opacity .3s linear, top .3s ease-out;-o-transition:opacity .3s linear, top .3s ease-out;transition:opacity .3s linear, top .3s ease-out;top:-25%;}
        .modal.fade.in{top:10%;}
        .modal-header{padding:9px 15px;border-bottom:1px solid #eee;}.modal-header .close{margin-top:2px;}
        .modal-header h3{margin:0;line-height:30px;}
        .modal-body{position:relative;overflow-y:auto;max-height:400px;padding:15px;}
        .modal-form{margin-bottom:0;}
        .modal-footer{padding:14px 15px 15px;margin-bottom:0;text-align:right;background-color:#f5f5f5;border-top:1px solid #ddd;-webkit-border-radius:0 0 6px 6px;-moz-border-radius:0 0 6px 6px;border-radius:0 0 6px 6px;-webkit-box-shadow:inset 0 1px 0 #ffffff;-moz-box-shadow:inset 0 1px 0 #ffffff;box-shadow:inset 0 1px 0 #ffffff;*zoom:1;}.modal-footer:before,.modal-footer:after{display:table;content:"";line-height:0;}
        .modal-footer:after{clear:both;}
        .modal-footer .btn+.btn{margin-left:5px;margin-bottom:0;}
        .modal-footer .btn-group .btn+.btn{margin-left:-1px;}
        .modal-footer .btn-block+.btn-block{margin-left:0;}
        .modal1 {
            display:    none;
            position:   fixed;
            z-index:    1000;
            top:        0;
            left:       0;
            height:     100%;
            width:      100%;
            background: white
                50% 50% 
                no-repeat;
            opacity:0.8;
        }

        /* When the body has the loading class, we turn
           the scrollbar off with overflow:hidden */
        body.loading {
            overflow: hidden;   
        }

        /* Anytime the body has the loading class, our
           modal element will be visible */
        body.loading .modal1 {
            display: block;
        }
    </style>  

    <?php 
        //Get existing terms
          $existing = $file_toArray->csv_existingTerm();
                          
     ?>
   <!--Modal-->
    <div id="form-content" class="modal hide fade in" style="display: none;">
       
        <div class="modal-header">
             <a class="close" data-dismiss="modal">×</a>
            <?php 
               if(!isset($m_term))
				{
					 $m_term = "";
				}
            ?>
            <h3>Assign GID for <font style="color:#08c;"><?php echo $m_term ?> </font></h3>
        </div>
        <div class="modal-body">
            <form class="contact" name="contact">

                <table class="table table-hover">
                    <tbody><thead>
                        <tr>
                            <th></th>
                            <th>GPID1</th>
                            <th>GPID2</th>
                            <th>GID</th>
                            <th>Method Type</th>
                            <th>Location</th>
                        </tr>
                    </thead>

                    <?php
                      for ($j = 0; $j < count($existing); $j++) {
                        if ($m_term === $existing[$j][1] && $existing[$j][0] === $m_id) {
                            echo '<tr>';
                            echo "<td>";
                            echo '<input type = "radio" name="choose" value="' . $existing[$j][2] . '">' . '</option>';
                            echo '<input type="hidden" name="term" value="' . $m_term . '" />';
                            echo '<input type="hidden" name="id" value="' . $m_id . '" />';
                            echo '<input type="hidden" name="pedigree" value="' . $m_pedigree . '" />';
                            echo '<input type="hidden" name="fid" value="' . $m_fid . '" />';
                            echo '<input type="hidden" name="mid" value="' . $m_mid . '" />';
                            echo '<input type="hidden" name="female" value="' . $m_female . '" />';
                            echo '<input type="hidden" name="male" value="' . $m_male . '" />';
                            echo "</td>";
                            echo "<td>(" . $existing[$j][2] . ")&nbsp; " . $existing[$j][3] . "</td>";
                            echo "<td>(" . $existing[$j][4] . ")&nbsp; " . $existing[$j][5] . "</td>";
                            echo "<td>" . $existing[$j][6] . "</td>";
                            echo "<td>(" . $existing[$j][7] . ")&nbsp; " . $existing[$j][8] . "</td>";
                            echo "<td>(" . $existing[$j][9] . ")&nbsp;" . $existing[$j][10] . "</td>";
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody> 
                </table>
            </form>
        </div>
        <div class="modal-footer">
            <input class="btn btn-primary" type="submit" value="Assign" id="submit">

            <a href="#" class="btn" data-dismiss="modal">Cancel</a>
        </div>
    </div>

    <div id="form-confirm" class="modal hide fade in" style="display: none;">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>

        </div>
        <div class="modal-body">
            <form class="confirm" name="contact">
                <input type="hidden" name="yes" value="1" />
            </form>
        </div>
        <div class="modal-footer">
            <input class="btn btn-primary" type="submit" value="Yes" id="submit">

            <a href="#" class="btn" data-dismiss="modal">Cancel</a>
        </div>
    </div>


    <div id="wait" class="modal1" >
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h3>Assign GID for <font style="color:#08c;"><?php echo $m_term ?> </font></h3>
        </div>
        <div class="modal-body">
            <center>
			<br>
			<br>
			<br>
			<br><br>
                <img src="./images/loading.gif" />
            </center>
        </div>
    </div>    

    <script type="text/javascript" src="assets/bootstrap.min.js"></script>
    <script type="text/javascript" src="./assets/pnotify-1.2.0/jquery.pnotify.js"></script>
    <script type="text/javascript">

    function show(var1, var2, var3, var4) {
        $.pnotify(
                {
                    text: var4 + "/" + var1 + " rows selected",
                    type: "info",
                    hide: false,
                    //shadow: false,
                    //opacity: .8
                    //nonblock: true,
                    //nonblock_opacity: .2
                });

        $.pnotify(
                {
                    text: var2 + " created GID(s)",
                    type: "success",
                    hide: false,
                    //shadow: false,
                    //nonblock: true,
                    //nonblock_opacity: .2
                });

      
    }
    ;
	

    $(document).ready(function() {
        $("input#submit").click(function() {
            $.ajax({
                type: "POST",
                data: $('form.contact').serialize(),
                beforeSend: function() {
                    //$("#form-content").modal('hide');
                    $('#wait').show();
                },
                success: function() {
                    //$("#GermplasmList").submit();
                    $('#wait').hide();
                    document.location.reload();
                },
                error: function() {
                    alert("failure");
                }
            });
            return false;
        });
    });
    $(document).ready(function() {
        var msg = 'You have reached the last row selected.Do you want to proceed to next entry?';
        $('a[data-confirm]').click(function(ev) {
            var href = $(this).attr('href');
            if (!$('#dataConfirmModal').length) {
                $('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><div id="dataConfirmLabel">You have reached the last row selected.Do you want to proceed to the next entry?</div></div><div class="modal-body"></div><div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button><a class="btn btn-primary" id="dataConfirmOK">OK</a></div></div>');
            }
            $('#dataConfirmModal').find('.modal-body').text($().attr('data-confirm'));
            $('#dataConfirmOK').attr('href', href);

            $('#dataConfirmModal').modal({show: true});

            return false;
        });
    });
    </script>
