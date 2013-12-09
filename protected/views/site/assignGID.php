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

            /*
              count all rows
             */
            $row_count = count($list);
            // echo "<br><br><br><br><br><br><br><br><br><br>all:".$row_count;
            /* END count all rows */

            /*
              count for rows that are done processing
             */
            $processed = count($checked) - 1;
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
                                <div class="panel panel-default" style="font-size: 11px;text-align: left;">
                                    <div class="panel-heading">
                                        <h3 style=" border-bottom: 0px solid #999; color:#666">Created GID for cross <font style="color:#e13300; "> <?php echo $pages[0][count($pages[0]) - 1][2]; ?></font></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="bs-callout bs-callout-warning">
                                            <div class="close" data-dismiss="alert">&times;</div>
                                            <h4>Summary info</h4>
                                            <p>
                                                <?php
                                                $female_id = (int) $pages[0][0][0];
                                                $i = 0;
                                                $male_id = $file_toArray->output_tree_json($pages); // get what ith element in the array is the male parent
                                                foreach ($pages[0] as $r) : list($id, $nval, $term, $GID, $methodID, $method, $locID, $location, $gpid1, $gpid2, $newGID) = $r;
                                                    if ($i == 0) {
                                                        if ($GID === "CHOOSE GID") {
                                                            if ($i + 1 == $male_id) {
                                                                echo "The female parent <b>" . $term . "</b> has multiple matches in the database. Please click the link and choose a GID <br>";
                                                            } else {
                                                                echo "The female parent <b>" . $term . "</b> has multiple matches in the database. Please choose a GID to set the GID(s) of the preceeding pedigree(s)<br>";
                                                            }
                                                        } else {
                                                            if ($newGID === "new") {
                                                                echo "The female parent <b>" . $term . "</b> has been added to the local database with a suggested method of <b>(" . $methodID . ")" . $method . "</b>. You may change the method type by 
															typing the method id, type, code, or name and clicking the update button.
															<br>";
                                                            } else {
                                                                if ($i + 1 == $male_id) {
                                                                    echo "The female parent <b>" . $term . "</b> has a single hit search from the local or central database.<br>";
                                                                } else {
                                                                    echo "The female parent <b>" . $term . "</b> has a single hit search from the local or central database. Sequentially, the preceeding line(s) is/are also set.<br>";
                                                                }
                                                            }
                                                        }
                                                    } else if ($i == $male_id) {
                                                        if ($GID === "CHOOSE GID") {
                                                            if (($i + 1) == count($pages[0]) - 1) {
                                                                echo "The male parent <b>" . $term . "</b> has multiple matches in the database. Please click the link and choose a GID<br>";
                                                            } else {
                                                                echo "The male parent <b>" . $term . "</b> has multiple matches in the database. Please choose a GID to set the GID(s) of the preceeding pedigree(s)<br>";
                                                            }
                                                        } else {
                                                            if ($newGID === "new") {
                                                                echo "The male parent <b>" . $term . "</b> has been added to the local database with a suggested method of <b>(" . $methodID . ")" . $method . "</b>. You may change the method type by 
															typing the method id, type, code, or name and clicking the update button.
															<br>";
                                                            } else {
                                                                if (($i + 1) == count($pages[0]) - 1) {
                                                                    echo "The male parent <b>" . $term . "</b> has a single hit search from the local or central database.<br>";
                                                                } else {
                                                                    echo "The male parent <b>" . $term . "</b> has a single hit search from the local or central database. Sequentially, the preceeding line(s) is/are also set.<br>";
                                                                }
                                                            }
                                                        }
                                                    }$i++;

                                                    if ($id == $fid . "/" . $mid) {
                                                        if ($newGID === "new") {
                                                            echo "The cross <b>" . $term . "</b> has been added to the local database with a suggested method of <b>(" . $methodID . ")" . $method . "</b>. You may change the method type by 
														typing the method id, type, code, or name and clicking the update button.
															<br>";
                                                        } else {
                                                            echo "The cross <b>" . $term . "</b> is already existing in the local or central database.<br>";
                                                        }
                                                    }
                                                endforeach;
                                                //}
                                                ?>
                                            </p>
                                        </div>			
                                    </div>
                                    <table class="table table-hover table-condensed">

                                        <thead>
                                        <th></th>
                                        <th></th>
                                        <th>Germplasm Name</th>
                                        <th>GID</th>
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

                                                    echo "<td width='20px;'><span class='label label-important'>New</span></td>";
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
                                                    echo "<td><a  data-toggle='modal' href='#new-Modal' class='open-dialog' data-id='$term'>Choose GID</a></td>";
                                                    echo "<input type='hidden' class='$term' name='m_id' id='m_id' value='$id'>";
                                                    echo "<input type='hidden' class='$term' name='m_pedigree' id='m_pedigree' value='$nval'>";
                                                    echo "<input type='hidden' class='$term' name='m_nval' id='m_val' value='$nval'>";
                                                    echo "<input type='hidden' class='$term' name='m_mid' value='$mid'>";
                                                    echo "<input type='hidden' class='$term' name='m_fid' value='$fid'>";
                                                    echo "<input type='hidden' class='$term' name='m_female' value='$female'>";
                                                    echo "<input type='hidden' class='$term' name='m_male' value='$male'>";
                                                    echo "<input type='hidden' class='$term' name='list' value='" . base64_encode(serialize($list)) . "'>";
                                                    echo "<input type='hidden' class='$term' name='createdGID' value='" . base64_encode(serialize($createdGID)) . "'>";
                                                    echo "<input type='hidden' class='$term' name='existing' value='" . base64_encode(serialize($existing)) . "'>";
                                                    echo "<input type='hidden' class='$term' name='checked' value='" . base64_encode(serialize($checked)) . "'>";
                                                    echo "<input type='hidden' class='$term' name='locationID' value='" . $locationID . "'>";

                                                    $m_term = $term;
                                                    $m_id = $id;
                                                    $m_pedigree = $nval;
                                                    $m_nval = $nval;
                                                    $m_mid = $mid;
                                                    $m_fid = $fid;
                                                    $m_female = $female;
                                                    $m_male = $male;
                                                } elseif ($GID === "DUPLICATE" || $GID === "NOT SET" || $GID === "Does not exist") {
                                                    if ($GID === "Does not exist") {
                                                        echo "<td><span class='label label-inverse'>" . $GID . "</span></td>";
                                                    } elseif ($GID === "NOT SET") {
                                                        echo "<td class='muted'><font><b><i>" . $GID . "</i></b></font></td>";
                                                    }
                                                } else {
                                                    echo "<td>" . $GID . "</td>";
                                                }
                                                //Methods

                                                if ($newGID == "new") {
                                                    ?><td>
                                                    <form action="" method="POST" id="updateMethodForm" name="updateMethodForm">
                                                        <?php
                                                        $line = array();
                                                        $line = explode("#", $method);
                                                        $line = implode(",", $line);
                                                        $method = $line;
                                                        echo "(" . $methodID . ")&nbsp; " . $method . "<br>";
                                                        echo CHtml::hiddenField("selectMethod", "0");
                                                        echo CHtml::hiddenField("proceedMethod", "0");
                                                        echo CHtml::hiddenField("term", $term)
                                                        ?>
                                                        <input
                                                            id="other<?php echo $j; ?>"
                                                            type="text"
                                                            class="ta"
                                                            placeholder="Type the method id/type/code/name"
                                                            autocomplete="off"
                                                            data-provide="typeahead"
                                                            value=""
                                                            />
                                                        <br/>
                                                        <?php
                                                        echo '<input type="hidden" class="' . $j . '" id="term' . $j . '" name="list" value="' . base64_encode(serialize($list)) . '" />';
                                                        echo '<input type="hidden" class="' . $j . '" id="term' . $j . '" name="createdGID" value="' . base64_encode(serialize($createdGID)) . '" />';
                                                        echo '<input type="hidden" class="' . $j . '" id="term' . $j . '" name="existing" value="' . base64_encode(serialize($existing)) . '" />';
                                                        echo '<input type="hidden" class="' . $j . '"  id="term' . $j . '" name="checked" value="' . base64_encode(serialize($checked)) . '" />';
                                                        echo '<input type="hidden" class="' . $j . '" id="term' . $j . '"name="locationID" value="' . $locationID . '" />';
                                                        ?>
                                                        <input type="hidden" class="<?php echo $j; ?>" name="term" id="term<?php echo $j ?>" value="$term"/>
                                                        <input type="hidden" class="<?php echo $j; ?>" name="bondId" id="bondId<?php echo $j; ?>" value="" />
                                                        <input type="hidden" class="<?php echo $j; ?>" name="gid" id="gid<?php echo $j; ?>" value="<?php echo $GID; ?>" />
                                                        <input type="hidden" class="<?php echo $j; ?>" name="id" id="id<?php echo $j; ?>" value="<?php echo $id; ?>" />
                                                        <div id="instruction" class="muted" style="font-size:10px;"></div>                                                        
                                                        <input type="submit" id="submitMethod<?php echo $j; ?>" class="btn btn-primary btn-mini" value="Update" onclick=" return jsConfirmDialog(<?php echo $j; ?>);
                        return false;" ><br><br>
                                                    </form>
                                                    <?php
                                                    $j++;
                                                } else {
                                                    $line = array();
                                                    $line = explode("#", $method);
                                                    $line = implode(",", $line);
                                                    $method = $line;
                                                    echo "<td>(" . $methodID . ")&nbsp; " . $method . "</td>";
                                                }
                                                $line = array();
                                                $line = explode("#", $location);
                                                $line = implode(",", $line);
                                                $location = $line;
                                                echo "<td>(" . $locID . ")&nbsp; " . $location . "</td>";
                                                echo '</tr>';

                                            endforeach;
                                            ?>
                                            </tbody>
                                    </table>
                                    <?php
                                    // print out the page numbers beneath the results
                                    //$pageNumbers = $pagination->getLinks2($_GET, $processed, $row_count, $not_standard);
                                    $pageNumbers = $pagination->getLinks2($_GET, $processed, $row_count);
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
                    <!-- </div>
                 </div>
                    -->
                    <!-- <div class="span7">
                         <div class="area">
                    -->
                    <br>
                    <br><br>

                    <div id="GermplasmList">
                        <?php
//include($_SERVER['DOCUMENT_ROOT'] . "/PedigreeImport/list.php");
                        include(dirname(__FILE__) . "/createdGID.php");
                        /* Yii::import('application.controllers.SiteController');
                          $controller_instance = new SiteController("Site"); //string $id, CWebModule $module=NULL
                          $controller_instance->actionCreatedGID();
                         * 
                         */
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
<div id="method-confirm" title="Confirm">Are you sure you want to update the method?</div>

<script type="text/javascript" src="./assets/pnotify-1.2.0/jquery.pnotify.js"></script>
<script type="text/javascript">
    function storeLocal() {
        if ('localStorage' in window && window['localStorage'] != null) {
            try {
                console.log(JSON.stringify(<?php echo json_encode($list); ?>));
                console.log(JSON.stringify(<?php echo json_encode($createdGID); ?>));
                console.log(JSON.stringify(<?php echo json_encode($existing); ?>));
                localStorage.setItem('list', JSON.stringify(<?php echo json_encode($list); ?>));
                localStorage.setItem('createdGID', JSON.stringify(<?php echo json_encode($createdGID); ?>));
                localStorage.setItem('existing', JSON.stringify(<?php echo json_encode($existing); ?>));
                localStorage.setItem('checked', JSON.stringify(<?php echo json_encode($checked); ?>));
            } catch (e) {
                if (e === QUOTA_EXCEEDED_ERR) {
                    alert('Quota exceeded!');
                }
            }
        } else {
            alert('Cannot store user preferences as your browser do not support local storage');
        }
    }
    window.addEventListener('storage', storageEventHandler, false);
    function storageEventHandler(event) {
        storeLocal();
    }
</script>
<script type="text/javascript">
    function jsConfirmDialog(count) {
        event.preventDefault();

        var bondId = $("#bondId" + count).val();
        var gid = $("#gid" + count).val();
        var id = $("#id" + count).val();
        var array_1 = new Array(bondId, gid, id);
        //*****assign the array value to a hidden field inside method POST
        $("#selectMethod").val(array_1);
        //****opens the confirmation modal for update method
        $("#method-confirm").dialog('open');

    }
    ;
    function methodLoading() {
        $('#screen').css({opacity: 0.4, 'width': $(document).width(), 'height': $(document).height()});
        $('body').css({'overflow': 'hidden'});
        $('#ajax-loading-indicator').css({'display': 'block'});
        notifyMethod();
    }
    function notifyMethod() {
        var term1 = $("#term").val();
        $.pnotify({
            text: "You have successfully updated the method for germplasm" + " " + "'" + term1 + "'" + ".",
            type: "success",
            hide: false
        });
    }
    $(document).ready(function() {
        $("#method-confirm").dialog({
            autoOpen: false,
            autoClose: false,
            resizable: false,
            height: 180,
            modal: true,
            buttons: [{
                    text: 'Yes',
                    click: function() {
                        $(this).dialog('close');
                        var form = $("#updateMethodForm");
                        form.submit();
                        methodLoading();

                        return true;
                    }
                }, {
                    text: 'No',
                    click: function() {
                        $(this).dialog('close');
                        return false;
                    }
                }]
        });
    });
    function show(row_count, newGID_count, not_standard, to_process) {
    storeLocal();
        $.pnotify(
                {
                    text: to_process + "/" + row_count + " rows selected",
                    //text: ,
                    type: "info",
                    hide: false,
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
                    hide: false,
                    //shadow: false,
                    //nonblock: true,
                    //nonblock_opacity: .2
                });
        if (not_standard > 0) {
            $.pnotify(
                    {
                        text: not_standard + " row(s) <br>" + "<b>not in standard form</b>",
                        type: "error",
                        hide: false,
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
        var arr = document.getElementsByClassName(term);

        var m_values = new Array();
        m_values.length = 0;
        for (var i = 0; i < arr.length; i++) {
            m_values.push(arr[i].value);
        }

        //******assign the obtained value in the modal*****
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'modules_folder/chooseGID.php',
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

    //******gets the value inside the textbox of the typeahead********
    $(document).ready(function() {
        //***Typeahead****
        $.each(document.getElementsByClassName("ta"), function(index, value) {
            //console.log(value['id']);
            $('#' + value['id']).typeahead({
                source: function(query, process) {

                    $.ajax({
                        url: 'methods.json'
                                , cache: false
                                , success: function(data) {

                            objects = [];
                            map = {};
                            $.each(data, function(i, object) {

                                //for each iteration of this loop the "object" argument contains
                                //1 bond object from the array in our json, such as:
                                // { "id":7, "name":"Pierce Brosnan" }

                                //add the label to the display array

                                //also store a hashmap so that when bootstrap gives us the selected
                                //name we can map that back to an id value

                                map[object.mid + "," + object.mcode + "," + object.mtype + "," + object.mname] = object;
                                objects.push(object.mid + "," + object.mcode + "," + object.mtype + "," + object.mname);
                            });

                            //send the array of results to bootstrap for display
                            process(objects);
                        }
                    });
                },
                updater: function(object) {
                    // $('#getSelection<?php //echo $i;  ?>').val(map[object].label);
                    $('#bondId' + index).val(map[object].mid);
                    return object;
                }
            });

        });
        //****End of Type ahead****

    });
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
                                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�\n\
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
