<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--div to grey out the screen while loading indicator is on-->
<div id='screen'>
</div>
<span id="ajax-loading-indicator">
</span>
<!---End for loading indicators-->
<?php
Yii::import('application.modules.file_toArray');
Yii::import('application.modules.json');
Yii::import('application.modules.curl');
Yii::import('application.modules.pagination');
Yii::import('application.modules.configDB');
Yii::import('application.modules.model');

$file_toArray = new file_toArray();
$unselected = 0;
//print_r($checked);
//print_r($createdGID);
// final is the array containing arrays of the pedigree lines (from the checkedboxes)
$final = $file_toArray->getPedigreeLine($checked, $createdGID);
if (count($final)) {
//*****Create the pagination object
    $pagination = new pagination($final, (isset($_GET['pagea']) ? $_GET['pagea'] : 1), 1);
//******Decide if the first and last links should show
    $pagination->setShowFirstAndLast(false);
// Parse through the pagination class
    $pages = $pagination->getResults();

// If we have items 
    if (count($pages) != 0) {
// Create the page numbers
// Loop through all the items in the array

        $count = 0;
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

        //  count all rows
        if (isset($list)) {
            $row_count = count($list);
        } else {
            $row_count = $_POST['row_count'];
        }
        /* END count all rows */

        /*
          count for rows that are done processing
         */
        $processed = count($checked);
//echo "<br>".$processed . " rows selected<br>";
        /* END count for rows that are done processing */
        /*
          count for unstandardized germplasm names
         */
        $unselected = $file_toArray->get_unselected_rows($checked, $list);
        $standard = count($file_toArray->checkIf_standardize($unselected, $list));
        $not_standard = count($unselected) - $standard;

        /*
          END count for unstandardized germplasm names
         */

        /* count new GID created for cross names
         */
        $GID_rows = $file_toArray->csv_corrected_GID($list);
        $newGID_count = count($GID_rows);
//echo "<br>".count($GID_rows) . " created GID(s)<br>";

        /* END count new GID created for cross names */
        ?>
        <body id="page" data-spy="scroll"  onload="show(<?php echo $row_count; ?>,<?php echo $newGID_count; ?>,<?php echo $not_standard; ?>,<?php echo $processed; ?>);">
            <!--<link href="assets/bootstrap-responsive.css" rel="stylesheet" type="text/css">-->

            <link href="./assets/pnotify-1.2.0/jquery.pnotify.default.css" rel="stylesheet" type="text/css">
            <link href="./assets/pnotify-1.2.0/jquery.pnotify.default.icons.css" rel="stylesheet" type="text/css">
            <!-- <link href="assets/GM_bootstrap.css" rel="stylesheet" type="text/css"> -->

            <div class="container" >
                <div class="page-points">
                    <br>
                </div>
                <div id="sections">
                    <div class="row-fluid">
                        <div class="span10">
                            <!--<div class="span8">
                                <div class="area">
                            -->
                            <div id="data">
                                <div class="panel panel-default" style="font-size: 11px;text-align: left;  background-color: #ffffff;">
                                    <div class="panel-heading" style="border-top-color: #dddddd; padding: 5px 10px;
                                         ">
                                        <h3 style=" border-bottom: 0px solid #999; color:#666">Assign GID for cross <font style="color:#e13300; "> <?php echo $pages[0][count($pages[0]) - 1][2]; ?></font></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="bs-callout bs-callout-warning" style="background-color: #f0f7fd; border-color: #d0e3f0; margin: 20px 0; padding: 10px 25px 10px 10px; border-left: 5px solid #eee; ">

                                            <h5>Summary info</h5>
                                            <p>
                                                <?php
                                                $female_id = (int) $pages[0][0][0];
                                                $i = 0;
                                                $male_id = $file_toArray->output_tree_json($pages); // get what ith element in the array is the male parent

                                                foreach ($pages[0] as $r) : list($id, $nval, $term, $GID, $methodID, $method, $locID, $location, $gpid1, $gpid2, $newGID) = $r;
                                                    if ($id == $fid . "/" . $mid) {

                                                        if ($GID !== "NOT SET" && $GID !== "CHOOSE GID") {
                                                            if ($newGID === "new") {
                                                                echo "The cross <b>" . $term . "</b> has been added to the local database.
															<br>";
                                                            } else {
                                                                echo "You have chosen an existing cross <b>" . $term . "</b> from the local or central database.<br>";
                                                            }
                                                        } else if ($GID == "NOT SET") {
                                                            echo "Set <b>first</b> the GID(s) of the cross' female and/or male parent.<br>";
                                                        } else if ($GID == "CHOOSE GID") {
                                                            echo "Choose among existing cross names or create new GID.<br>";
                                                        }
                                                    }
                                                endforeach;

                                                foreach ($pages[0] as $r) : list($id, $nval, $term, $GID, $methodID, $method, $locID, $location, $gpid1, $gpid2, $newGID) = $r;
                                                    if ($i == 0) {
                                                        if ($GID === "CHOOSE GID") {
                                                            if ($i + 1 == $male_id) {
                                                                echo "The female parent <b>" . $term . "</b> has multiple matches in the database. Please click the link and choose a GID <br>";
                                                            } else {
                                                                echo "The female parent <b>" . $term . "</b> has multiple matches in the database. Please choose a GID to set the GID(s) of the preceeding pedigree(s)<br>";
                                                            }
                                                        } else if ($GID !== "NOT SET") {
                                                            if ($newGID === "new") {
                                                                echo "The female parent <b>" . $term . "</b> has been added to the local database.
															<br>";
                                                            } else {
                                                                if ($i + 1 == $male_id) {
                                                                    echo "The female parent <b>" . $term . "</b> has a single hit search from the local or central database.<br>";
                                                                } else {
                                                                    echo "The female parent <b>" . $term . "</b> has a single hit search from the local or central database. Sequentially, the preceeding line(s) is/are also set.<br>";
                                                                }
                                                            }
                                                        } else {
                                                            echo "Set the pedigree line of female parent <b>" . $term . "</b>.<br>";
                                                        }
                                                    } else if ($i == $male_id) {
                                                        if ($GID === "CHOOSE GID") {
                                                            if (($i + 1) == count($pages[0]) - 1) {
                                                                echo "The male parent <b>" . $term . "</b> has multiple matches in the database. Please click the link and choose a GID<br>";
                                                            } else {
                                                                echo "The male parent <b>" . $term . "</b> has multiple matches in the database. Please choose a GID to set the GID(s) of the preceeding pedigree(s)<br>";
                                                            }
                                                        } else if ($GID !== "NOT SET") {
                                                            if ($newGID === "new") {
                                                                echo "The male parent <b>" . $term . "</b> has been added to the local database.
															<br>";
                                                            } else {
                                                                if (($i + 1) == count($pages[0]) - 1) {
                                                                    echo "The male parent <b>" . $term . "</b> has a single hit search from the local or central database.<br>";
                                                                } else {
                                                                    echo "The male parent <b>" . $term . "</b> has a single hit search from the local or central database. Sequentially, the preceeding line(s) is/are also set.<br>";
                                                                }
                                                            }
                                                        } else {
                                                            echo "Set the pedigree line of male parent <b>" . $term . "</b>.<br>";
                                                        }
                                                    }$i++;


                                                endforeach;
                                                //}
                                                ?>
                                            </p>
                                        </div>			
                                    </div>
                                    <!--Start of the first table which contains the selected germplasm and its details-->
                                    <table class="table table-hover table-condensed ">

                                        <thead>
                                        <th></th>
                                        <th></th>
                                        <th>Germplasm Name</th>
                                        <th>GID</th>
                                        <th>GPID1</th>
                                        <th>GPID2</th>
                                        <th>Method </th>
                                        <th>Location</th>

                                        </thead>
                                        <tbody>

                                            <?php
                                            $i = 0;
                                            $j = 0;
                                            foreach ($pages[0] as $r) : list($id, $nval, $term, $GID, $methodID, $method, $locID, $location, $gpid1, $gpid2, $newGID) = $r;

                                                if ($i == 0) {
                                                    echo '<tr style="border-left: 4px solid #f38787; /*background-color:rgba(243, 135, 135, 0.10);*/" > ';
                                                } elseif ($i == $male_id) {
                                                    echo '<tr style="border-left: 4px solid #65c465; /*background-color:rgba(0, 167, 83, 0.1)*/"> ';
                                                } else {
                                                    echo "<tr>";
                                                }
                                                if ($id === $fid) {
                                                    echo ' ';
                                                } elseif ($id === $mid) {
                                                    echo '';
                                                } elseif ($id === $fid . "/" . $mid) {
                                                    echo '<tr style="border-left: 4px solid #f1e7bc; background-color:#fefbed; ">';
                                                } else {
                                                    echo '';
                                                }


                                                if ($newGID === "new") {

                                                    echo "<td width='20px;'><span class='label label-success'>NEW</span></td>";
                                                } else {
                                                    echo "<td width='20px;'><span></span></td>";
                                                }

                                                if ((count($pages[0])) == $i) {
                                                    echo "<td width='20px;'></td>";
                                                }
                                                if ($i == 0) {
                                                    echo "<td width='20px;'><img  style='display: inline-block;width: 11px;height: 19px; '
														src='images/glyphicons_247_female2.png' /></td><td>" . $term . "</td>";
                                                } elseif ($i == $male_id) {
                                                    echo "<td width='20px;'><img  style='display: inline-block;width: 16px;height: '
														src='images/glyphicons_246_male2.png' /></td><td>" . $term . "</td>";
                                                } else {
                                                    echo "<td width='20px;'></td><td> " . $term . "</td>";
                                                }
                                                $i++;
                                                if ($GID === "CHOOSE GID") {
                                                    //echo "<td><a  data-toggle='modal' href='?term=" . $term . "&id=" . $id . "&pedigree=" . $nval . "&mid=" . $mid . "&fid=" . $fid . "&female=" . $female . "&male=" . $male . "#form-content' >Choose GID</a></td>";
                                                    echo "<td><a  data-toggle='modal' href='#new-Modal' class='open-dialog' data-id='$term.$j'>Choose GID</a></td>";
                                                    echo "<input type='hidden' class='$term.$j' name='m_id' id='m_id' value='$id'>";
                                                    echo "<input type='hidden' class='$term.$j' name='m_pedigree' id='m_pedigree' value='$nval'>";
                                                    echo "<input type='hidden' class='$term.$j' name='m_nval' id='m_val' value='$nval'>";
                                                    echo "<input type='hidden' class='$term.$j' name='m_mid' value='$mid'>";
                                                    echo "<input type='hidden' class='$term.$j' name='m_fid' value='$fid'>";
                                                    echo "<input type='hidden' class='$term.$j' name='m_female' value='$female'>";
                                                    echo "<input type='hidden' class='$term.$j' name='m_male' value='$male'>";
                                                    echo "<input type='hidden' class='$term.$j' name='list' value='" . base64_encode(serialize($list)) . "'>";
                                                    echo "<input type='hidden' class='$term.$j' name='createdGID' value='" . base64_encode(serialize($createdGID)) . "'>";
                                                    echo "<input type='hidden' class='$term.$j' name='existing' value='" . base64_encode(serialize($existing)) . "'>";
                                                    echo "<input type='hidden' class='$term.$j' name='checked' value='" . base64_encode(serialize($checked)) . "'>";
                                                    echo "<input type='hidden' class='$term.$j' name='locationID' value='" . $locationID . "'>";
                                                    echo "<input type='hidden' class='$term.$j' name='cross' value='" . $pages[0][count($pages[0]) - 1][2] . "'>";
                                                    echo "<input type='hidden' class='$term.$j' name='term' id='term' value='$term'>";
                                                    // echo $nval . "<br>";
                                                } elseif ($GID === "DUPLICATE" || $GID === "NOT SET" || $GID === "Does not exist") {
                                                    if ($GID === "Does not exist") {
                                                        echo "<td><span class='label label-inverse'>" . $GID . "</span></td>";
                                                    } elseif ($GID === "NOT SET") {
                                                        echo "<td class='muted'><font><b><i>" . $GID . "</i></b></font></td>";
                                                    }
                                                } else {
                                                    echo "<td>" . $GID . "</td>";
                                                }

                                                //gpid1
                                                if ($gpid1 === "N/A") {
                                                    echo "<td class='muted'><font><i>" . $gpid1 . "</i></font></td>";
                                                } else {
                                                    echo "<td>" . $gpid1 . "</td>";
                                                }

                                                //gpid2
                                                if ($gpid2 === "N/A") {
                                                    echo "<td class='muted'><font><i>" . $gpid2 . "</i></font></td>";
                                                } else {
                                                    echo "<td>" . $gpid2 . "</td>";
                                                }
                                                //Methods

                                                if ($method === "N/A") {
                                                    echo "<td class='muted'><font><i>" . $method . "</i></font></td>";
                                                } else {
                                                    $line = array();
                                                    $line = explode("#", $method);
                                                    $line = implode(",", $line);
                                                    $method = $line;
                                                    echo "<td>" . $methodID . "&nbsp; <i>" . $method . "</i></td>";
                                                }
                                                // location
                                                if ($method === "N/A") {
                                                    echo "<td class='muted'><font><i>" . $method . "</i></font></td>";
                                                } else {
                                                    $line = array();
                                                    $line = explode("#", $location);
                                                    $line = implode(",", $line);
                                                    $location = $line;
                                                    echo "<td>" . $locID . "&nbsp; <i>" . $location . "</i></td>";
                                                    echo '</tr>';
                                                }
                                                $j++;
                                            endforeach;
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php
                                    // print out the page numbers beneath the results
                                    //$pageNumbers = $pagination->getLinks2($_GET, $processed, $row_count, $not_standard);
                                    //echo "<br>row_count: ".$row_count;
                                    // echo "<br>processed: ".$processed;


                                    $pageNumbers = $pagination->getLinks2($_GET, $processed, $row_count);
                                    echo " <div class='panel-footer' style='border-bottom-color: #dddddd; padding: 10px 15px;background-color: #f5f5f5;border-top: 1px solid #dddddd;border-bottom-right-radius: border-bottom-left-radius: 3px;'>";
                                    echo "<ul class='pager'>";
                                    echo $pageNumbers;
                                    echo '</ul>';
                                    echo "</div>";

                                    /* $pagination2 = '';
                                      if(count($pages) > 1){
                                      $pagination2 .='<ul class="paginate">';
                                      for($i=1; $i<count($pages);$i++){
                                      $pagination2 .= '<li><a href="#" class="paginate_click" id="'.$i.'-page">'.$i.'</a></li>';
                                      }
                                      $pagination2 .= '</ul>';
                                      } */
                                }
                            }
                            ?>

                        </div>	
                    </div>

                    <br>
                    <br><br>

                    <div id="GermplasmList">
                        <?php if (!isset($_POST['next'])) { ?>
                            <h4 style=" border-bottom: 0px solid #999;text-align: left;">Germplasm List</h4> 
                        <?php } ?>
                        <?php
                        $this->widget('ext.selgridview.BootSelGridView', array(
                            'id' => 'germplasmList',
                            'dataProvider' => $GdataProvider,
                            'filter' => $filtersForm,
                            'enablePagination' => true,
                            'ajaxUpdate' => true,
                            'beforeAjaxUpdate' => 'js:
					function (id, options) {
						options.data = {
							list: $("#list").val(),
                                                        rows: $("#list").val(),
							locationID: $("#location").val(),
							location: $("#location").val(),
                                                        checked: $("#checked").val(),
                                                        existing: $("#existing").val(),
                                                        createdGID: $("#createdGID").val(),
                                                        row_count: $("#row_count").val(),
							next:1
						};
						options.type = "post";
					}
				',
                            'columns' => array(
                                array(
                                    'header' => 'Cross Name',
                                    'value' => 'CHtml::encode($data["nval"])',
                                    'name' => '',
                                    'filter' => CHtml::textField('FilterPedigreeForm[nval]', isset($_GET['FilterPedigreeForm']['nval]']) ? $_GET['FilterPedigreeForm']['nval'] : ''),
                                ),
                                array(
                                    'header' => 'GID',
                                    'name' => 'gid',
                                    'value' => 'CHtml::encode($data["gid"])',
                                ),
                                array(
                                    'header' => 'Female Parent',
                                    'name' => 'female',
                                    'type' => 'raw',
                                    'value' => function($data) {
                                        $line = array();
                                        $line = explode("#", CHtml::encode($data["fremarks"]));
                                        $line = implode("\n", $line);
                                        $fremarks = $line;
                                        if (strcmp($fremarks, 'in standardized format') == 0) {
                                            $your_array = array();
                                            $your_array = explode("#", CHtml::encode($data["fgid"]));
                                            $your_array = implode("<br>", $your_array);
                                            $fgid = $your_array;

                                            return "<b>" . CHtml::tag("span", array("title" => CHtml::encode($data["fremarks"]), "class" => "tooltipster"), CHtml::encode($data["female"])) . "</b>" . "" . $fgid . "";
                                        } else {
                                            return "<font style='color:#FF6600; '>" . CHtml::tag("span", array("title" => $fremarks, "class" => "tooltipster"), CHtml::encode($data["female"])) . "</font>";
                                            //return "<div class='j'><font style='color:#FF6600; font-weight:bold;'>" . CHtml::link(CHtml::encode($data["female"]), Yii::app()->createUrl("site/editGermplasm", array("germplasm" => $data["female"], "error" => $data["fremarks"])), array('title' => CHtml::encode($data["fremarks"]), 'class' => 'tooltipster')) . "</font></div>";
                                            // return '<a data-toggle="tooltip" title="' .CHtml::encode($data["mremarks"]) . '" data-placement="right" style="color:rgb(255, 0, 0); font-weight:bold;" href="/GMGR/index.php?r=site/editGermplasm.php?germplasm=' .CHtml::encode($data["female"]) . '&error=' .CHtml::encode($data["fremarks"]). '">' . CHtml::encode($data["female"]) . '<a>';
                                        }
                                    },
                                ),
                                array(
                                    'header' => 'Male Parent',
                                    'name' => 'male',
                                    'type' => 'raw',
                                    'value' => function($data) {
                                        $line = array();
                                        $line = explode("#", CHtml::encode($data["mremarks"]));
                                        $line = implode("\n", $line);
                                        $mremarks = $line;
                                        if (strcmp($mremarks, 'in standardized format') == 0) {

                                            $your_array = array();
                                            $your_array = explode("#", CHtml::encode($data["mgid"]));
                                            $your_array = implode("<br>", $your_array);
                                            $mgid = $your_array;
                                            return "<b>" . CHtml::tag("span", array("title" => CHtml::encode($data["mremarks"]), "class" => "tooltipster"), CHtml::encode($data["male"])) . "</b>" . "" . $mgid . "";
                                        } else {
                                            return "<font style='color:#FF6600; '>" . CHtml::tag("span", array("title" => $mremarks, "class" => "tooltipster"), CHtml::encode($data["male"])) . "</font>";
                                            //turn "<div class='j'><font style='color:#FF6600; font-weight:bold;'>" . CHtml::link(CHtml::encode($data["male"]), Yii::app()->createUrl("site/editGermplasm", array("germplasm" => $data["male"], "error" => $data["mremarks"])), array('title' => CHtml::encode($data["mremarks"]), 'class' => 'tooltipster')) . "</font></div>";
                                            //echo '<a data-toggle="tooltip" data-placement="right" title="' . $mremarks . '" style="color:rgb(255, 0, 0); font-weight:bold;" href="/GMGR/index.php?r=site/editGermplasm.php?germplasm=' . CHtml::encode($data["male"]) . '&error=' . $mremarks . '">' . CHtml::encode($data["male"]) . '<a>';
                                        }
                                    },
                                ),
                                array(
                                    'header' => 'Date of Creation',
                                    'name' => 'date',
                                    'type' => 'raw',
                                    'value' => 'CHtml::encode($data["date"])'
                                ),
                            ),
                        ));
                        ?>

                        <?php
                        echo CHtml::hiddenField('list', base64_encode(serialize($list)));
                        echo CHtml::hiddenField('rows', base64_encode(serialize($list)));
                        echo CHtml::hiddenField('location', $locationID);
                        echo CHtml::hiddenField('locationID', $locationID);
                        echo CHtml::hiddenField('checked', base64_encode(serialize($checked)));
                        echo CHtml::hiddenField('existing', base64_encode(serialize($existing)));
                        echo CHtml::hiddenField('createdGID', base64_encode(serialize($createdGID)));
                        echo CHtml::hiddenField('local_db_host', '');
                        echo CHtml::hiddenField('local_db_name', '');
                        echo CHtml::hiddenField('local_db_port', '');
                        echo CHtml::hiddenField('local_db_username', '');
                        echo CHtml::hiddenField('local_db_password', '');
                        echo CHtml::hiddenField('central_db_host', '');
                        echo CHtml::hiddenField('central_db_name', '');
                        echo CHtml::hiddenField('central_db_port', '');
                        echo CHtml::hiddenField('central_db_username', '');
                        echo CHtml::hiddenField('central_db_password', '');
                        if (isset($_POST['row_count'])) {
                            echo CHtml::hiddenField('row_count', $_POST['row_count']);
                        }
                        else
                            echo CHtml::hiddenField('row_count', $row_count);
                        ?>
                    </div>
                    <!--  </div>
                  </div>
                    -->
                </div>
            </div>
        </div>
    </div>
</body>
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

<!--***************************Modal****************************-->
<div id="new-Modal" class="modal hide fade in" style="display: none;"></div>

<!---*****data -confirm modal****-->
<div id="method-confirm" title="Confirm" style="display: none;">Are you sure you want to update the method?</div>

<script type="text/javascript" src="./assets/pnotify-1.2.0/jquery.pnotify.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

       // storeLocal();

        $(".paginate_click").click(function(e) {

        });
    });
    function storeLocal() {
        if ('localStorage' in window && window['localStorage'] != null) {

            localStorage.removeItem("list");
            localStorage.removeItem("existing");

            localStorage.removeItem("createdGID");
            try {
                //console.log(JSON.stringify(<?php //echo json_encode($list);      ?>));
                //console.log(JSON.stringify(<?php //echo json_encode($createdGID);      ?>));
                // console.log("existing: "+JSON.stringify(<?php echo base64_encode(serialize($existing)); ?> ));

                localStorage.removeItem("list");
                localStorage.removeItem("existing");

                localStorage.removeItem("createdGID");
                localStorage.setItem('list', <?php echo json_encode(base64_encode(serialize($list))); ?>);
                localStorage.setItem('createdGID', <?php echo json_encode(base64_encode(serialize($createdGID))); ?>);
                localStorage.setItem('existing', <?php echo json_encode(base64_encode(serialize($existing))); ?>);
                localStorage.setItem('checked', <?php echo json_encode(base64_encode(serialize($checked))); ?>);


                document.getElementById('checked').value = localStorage.checked;
                document.getElementById('existing').value = localStorage.existing;
                document.getElementById('createdGID').value = localStorage.createdGID;

                //******get database settings
                document.getElementById('local_db_host').value = localStorage.local_database_host;
                document.getElementById('local_db_name').value = localStorage.local_database_name;
                document.getElementById('local_db_port').value = localStorage.local_database_port;
                document.getElementById('local_db_username').value = localStorage.local_database_username;
                document.getElementById('local_db_password').value = localStorage.local_database_password;
                document.getElementById('central_db_host').value = localStorage.central_database_host;
                document.getElementById('central_db_name').value = localStorage.central_database_name;
                document.getElementById('central_db_port').value = localStorage.central_database_port;
                document.getElementById('central_db_username').value = localStorage.central_database_username;
                document.getElementById('central_db_password').value = localStorage.central_database_password;
            } catch (exception) {
                if ((exception !== QUOTA_EXCEEDED_ERR) &&
                        (exception !== NS_ERROR_DOM_QUOTA_REACHED)) {
                    throw exception;
                }
            }
        } else {
            alert('Cannot store user preferences as your browser do not support local storage');
        }
    }
    window.addEventListener('storage', storageEventHandler, false);
    function storageEventHandler(event) {
        //storeLocal();
    }
</script>
<script type="text/javascript">

    function show(row_count, newGID_count, not_standard, to_process) {
        storeLocal();
        $.pnotify(
                {
                    text: to_process + "/" + row_count + " rows selected",
                    //text: ,
                    type: "info",
                    hide: true,
                    //shadow: false,
                    //opacity: .8
                    //nonblock: true,
                    //nonblock_opacity: .2
                });
        $.pnotify(
                {
                    text: newGID_count + " created GID(s)",
                    //text:,

                    type: "success",
                    hide: true,
                    //shadow: false,
                    //nonblock: true,
                    //nonblock_opacity: .2
                });
        if (not_standard > 0) {
            $.pnotify(
                    {
                        text: not_standard + " row(s) <br>" + "<b>not in standard form</b>",
                        type: "error",
                        hide: true,
                        //shadow: false,
                        //opacity: .8
                    });
        }

    }
    ;


    //************For opening a modal dialog***************
    $(document).on("click", ".open-dialog", function() {
        //*****the term to be placed on the heading in the modal

        var term = $(this).data("id");
        var arr = null;
        arr = document.getElementsByClassName(term);
        console.log("id: " + term);
        var m_values = new Array();
        m_values.length = 0;
        for (var i = 0; i < arr.length; i++) {
            m_values.push(arr[i].value);
        }
        //console.log(m_values);

        //******assign the obtained value in the modal*****
        $.ajax({
            cache: false,
            type: 'POST',
            url: '<?php echo Yii::app()->createUrl('site/chooseGID') ?>',
            data: {termId: term, arr_terms: m_values},
            success: function(data) {
                $("#new-Modal").html(data);

            }
        });
    });

</script>

<!--*******This script is for the typeahead in the update method****--->
<script type="text/javascript" src="./assets/typeahead.js"></script>

<!--******script for the update method confirmation******--->
<!--<script type="text/javascript" src="./js/methodConfirm.js"></script>-->



<script type="text/javascript">

    /*function sampleFunction(count) {
     var bondId =  $("#bondId"+count).val();
     var gid = $("#gid"+count).val();
     var id = $("#id"+count).val();
     var submitMethod = $("#submitMethod"+count).attr("id"); //gets the id of the submit method
     
     var array_1 = new Array(bondId,gid,id);
     
     $("#selectMethod").val(array_1);
     
     return false;
     }*/


    var pop = function() {
        $('#new-Modal').css({'z-index': '1000'});
        $('#screen').css({'opacity': '0.4', 'width': $(document).width(), 'height': $(document).height()});
        $('body').css({'overflow': 'hidden'});
        $('#ajax-loading-indicator').css({'display': 'block'});

    }
    $('#submit').click(pop);

    //end of dialog create
    // });


</script>
<script type="text/javascript">

    // var msg = 'You have reached the last row selected.Do you want to proceed to next entry?';
    $('a[data-confirm]').click(function(ev) {
        var href = $(this).attr('href');
        if (!$('#dataConfirmModal').length) {
            $('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-header">\n\
                                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;\n\
                                    </button>\n\
                                    <div id="dataConfirmLabel">\n\
                                        You have reached the last row selected.Do you want to proceed to the next entry?\n\
                                    </div>\n\
                                    </div><div class="modal-body"></div><div class="modal-footer">\n\
                                    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel\n\
                                    </button>\n\
                                    <a class="btn btn-primary" id="dataConfirmOK">OK</a>\n\
                                    </div>\n\
                                    </div>');
        }
        $('#dataConfirmModal').find('.modal-body').text($().attr('data-confirm'));
        $('#dataConfirmOK').attr('href', href);
        $('#dataConfirmModal').modal({show: true});
        return false;
    });
</script>