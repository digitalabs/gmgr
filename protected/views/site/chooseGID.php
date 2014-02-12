<script src='./js/jquery-1.4.4.min.js' type='text/javascript'></script>
<script src='./js/jquery.dataTables.js' type='text/javascript'></script>
<script src='./js/jquery.dataTables.columnFilter.js' type='text/javascript'></script>

<form class='contact' name='contact' action='' method='POST' id='choose-frm'>
    <div class='modal-header'>
        <a class='close' data-dismiss='modal'>X</a>
        <?php
        if (isset($_POST['termId'])) {
            $m_term = $_POST['termId'];
        } else {
            $m_term = '';
        }
        ?>
        <h3>Assign GID for <font style='color:#08c;'><?php echo $m_term ?> </font></h3>
    </div>
    <?php
//These are the contents of the modal-body
    if (isset($_POST['arr_terms'])) {
        $termArray = $_POST['arr_terms'];

        $m_id = $termArray[0];
        $m_pedigree = $termArray[1];
        $m_nval = $termArray[2];
        $m_mid = $termArray[3];
        $m_fid = $termArray[4];
        $m_female = $termArray[5];
        $m_male = $termArray[6];
        $list = unserialize(base64_decode($termArray[7]));
        $createdGID = unserialize(base64_decode($termArray[8]));
        $existing = unserialize(base64_decode($termArray[9]));
        $checked = unserialize(base64_decode($termArray[10]));
        $locationID = $termArray[11];
        $cross = $termArray[12];
    }

    if (count($existing) !== 0) {
        //// print_r($existing);
        ?>

        <div class='modal-body' >
            <div id='id-row-count'>

            </div>
            <?php
            for ($j = 0; $j < count($existing); $j++) {
                if ($m_term === $existing[$j][11] && $existing[$j][0] === $m_id) {
                    $index = $j;
                    break;
                }
            }
            //echo ''.$cross."<br>";
            //echo $existing[0][13] . "<br>" . $existing[0][12] . "<br>";
            //$existing[0][13] = '20140204';
            if ($existing[$index][13] == 'not specified') {
                echo 'The date of creation of the cross in the list uploaded is not specified. All candidate GID(s) will be shown.<br> Create NEW GID if what you are looking for do not exist.<br>
                <br>';
            }
            if (strcmp($existing[$index][13], $existing[$index][12]) == 0) {
                echo ' There are multiple matches in the database having the exact female and male name values, and the date specified in the list uploaded matches the GIDs date of creation. Which GID do you want to use?<br><br>';
            }
            if ($existing[$index][13] != 'not specified' && $existing[$index][13] != $existing[$index][12]) {
                //echo "<br>";
                //echo "No germplasm <br><br>";
                ?>
                <input type='hidden'  value="<?php echo $existing[0][13]; ?>"  id='sToId' name='sToId'>
                <input type='button' class='btn btn-success' value='Show Germplasm older than the cross' id='filter' onclick='change()'>&nbsp;&nbsp;
                <br><br>
                <?php
            }
            ?>

            <table class='table table-hover' id='model'>
                <thead>
                    <tr>
                        <th></th>
                        <th>GID</th>

                        <th>GPID1</th>
                        <th>GPID2</th>
                        <th>Method Type</th>
                        <th>Location</th>
                        <th>Date of Creation</th>
                    </tr>   
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    for ($j = 0; $j < count($existing); $j++) {
                        /* echo 'm_term: '.$m_term."<br>";
                          echo 'existing[j][1]: '.$existing[$j][1]."<br>";
                          echo 'm_id: '.$m_id."<br>";
                          echo 'existing[j][0]: '.$existing[$j][0]."<br>";
                         */
                        if ($m_term === $existing[$j][11] && $existing[$j][0] === $m_id) {
                            $date_creation = $existing[$j][12];

                            $locationID_l = $existing[$j][9];
                            $location_l = $existing[$j][10];
                            $methodID_l = $existing[$j][7];
                            $method_l = $existing[$j][8];
                            $gpid1_l = $existing[$j][2];
                            $gpid1_nval = $existing[$j][3];
                            $gpid2_l = $existing[$j][4];
                            $gpid2_nval = $existing[$j][5];
                            $gid_l = $existing[$j][6];

                            $line = array();
                            $line = explode('#', $method_l);
                            $line = implode(',', $line);
                            $method_l = $line;

                            $line = array();
                            $line = explode('#', $location_l);
                            $line = implode(',', $line);
                            $location_l = $line;

                            echo "<tr>";
                            echo "<td>";
                            echo "<input type = 'radio' name='choose' value='" . $gid_l . "' />";
                            echo "<input type='hidden' name='term' value='" . $m_term . "' />";
                            echo "<input type='hidden' name='id' value='" . $m_id . "' />";
                            echo "<input type='hidden' name='pedigree' value='" . $m_pedigree . "' />";
                            echo "<input type='hidden' name='fid' value='" . $m_fid . "' />";
                            echo "<input type='hidden' name='mid' value='" . $m_mid . "' />";
                            echo "<input type='hidden' name='female' value='" . $m_female . "' />";
                            echo "<input type='hidden' name='male' value='" . $m_male . "' />";
                            echo "<input type='hidden' name='list' value='" . base64_encode(serialize($list)) . "' />";
                            echo "<input type='hidden' name='createdGID' value='" . base64_encode(serialize($createdGID)) . "' />";
                            echo "<input type='hidden' name='existing' value='" . base64_encode(serialize($existing)) . "' />";
                            echo "<input type='hidden' name='checked' value='" . base64_encode(serialize($checked)) . "' />";
                            echo "<input type='hidden' name='locationID' value='" . $locationID . "' />";
                            echo "<input type='hidden' name='theParent' value='" . $m_nval . "' />";
                            echo "<input type='hidden' name='cross' value='" . $cross . "' />";
                            echo "<input type='hidden' name='gid' value='" . $gid_l . "' />";
                            echo "<input type='hidden' name='gpid1' value='" . $gpid1_l . "' />";
                            echo "<input type='hidden' name='gpid2' value='" . $gpid2_l . "' />";
                            echo "<input type='hidden' name='cdate' value='" . $existing[$index][13] . "' />";
                            echo "</td>";
                            echo "<td>" . $gid_l . "</td>";

                            /* echo "<td>" . $existing[$j][6] . "<form action='index.php?r=site/editor' method='post' target='_blank'>

                              <input type='hidden' name='inputGID' value='" . $existing[$j][6] . ''>
                              <input type='hidden' name='maxStep' value='">
                              <input type='submit' value='See Pedigree Tree'>
                              </form>

                              </td>"; */

                            echo "<td>(" . $gpid1_l . ")&nbsp; " . $gpid1_nval . "</td>";
                            echo "<td>(" . $gpid2_l . ")&nbsp; " . $gpid2_nval . "</td>";
                            echo "<td>(" . $methodID_l . ")&nbsp;  " . $method_l . "</td>";
                            echo "<td>(" . $locationID_l . ")&nbsp;" . $location_l . "</td>";
                            echo "<td>" . $date_creation . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div id='modal-pedTree' class='modal hide fade in' style='display: none; z-index:2000'></div>

        </div>
        <div class='modal-footer'>
                <input class='btn btn-primary' type='submit' value='Assign' id='id-submit' >
                </form> 
            
                <form action='' method='POST' style=" display:inline-block;" >
                    <?php
                    // if ($existing[0][13] == 'not specified') {
                    echo "<input type = 'hidden' name='createNew' value='" . $cross . "' />";
                    echo "<input type='hidden' name='theParent' value='" . $m_nval . "' />";
                    echo "<input type='hidden' name='term' value='" . $m_term . "' />";
                    echo "<input type='hidden' name='chosenID' value='" . $m_id . "' />";
                    echo "<input type = 'hidden' name='mid' value='" . $m_mid . "' />";
                    echo "<input type = 'hidden' name='fid' value='" . $m_fid . "' />";
                    echo "<input type = 'hidden' name='gpid2_nval' value='" . $gpid2_nval . "' />";
                    echo "<input type = 'hidden' name='gpid1_nval' value='" . $gpid1_nval . "' />";
                    echo "<input type='hidden' name='list' value='" . base64_encode(serialize($list)) . "' />";
                    echo "<input type='hidden' name='createdGID' value='" . base64_encode(serialize($createdGID)) . "' />";
                    echo "<input type='hidden' name='existing' value='" . base64_encode(serialize($existing)) . "' />";
                    echo "<input type='hidden' name='checked' value='" . base64_encode(serialize($checked)) . "' />";
                    echo "<input type='hidden' name='locationID' value='" . $locationID . "' />";
                    echo "<input type='hidden' name='cdate' value='" . $existing[$index][13] . "' />";
                    ?>

                    <input id='id-create-new' class='btn btn-success' type='submit' value='Create New'>

                </form>
                <?php
                // }
                ?>
            
            <a href='#' class='btn' data-dismiss='modal'>Cancel</a>

        </div>

        <?php
    }
    ?>
    <script type='text/javascript'>

                    //function for the loading indicator
                    //************For opening a modal dialog***************
                    $(document).ready(function() {
                        var oTable = $('#model').dataTable({
                            'bPaginate': false,
                            'bSort': false,
                            'bSearchable': false,
                            'bScrollCollapse': true,
                            'bPaginate': false
                        }).columnFilter({sPlaceHolder: 'head:after',
                            aoColumns: [{type: 'none'},
                                {type: 'text'},
                                {type: 'text'},
                                {type: 'text'},
                                {type: 'text'},
                                {type: 'text'},
                                {type: 'text'},
                                {type: 'text'}
                            ]

                        });
                        filterTable();
                        //console.log('count: ' + oTable.fnSettings().fnRecordsDisplay());
                    });
                    function filterTable() {

                        var elem = document.getElementById('filter');

                        elem.value = 'Show All';
                        iMax = $('#sToId').attr('value');

                        $.fn.dataTableExt.afnFiltering.push(
                                function(oSettings, aData, iDataIndex) {
                                    // 'date-range' is the id for my input
                                    var dateRange = $('#sToId').attr('value');

                                    // parse the range from a single field into min and max, remove ' - '
                                    iMin = '';

                                    // 4 here is the column where my dates are.
                                    var iValue = aData[1];

                                    if (iMax == '') {
                                        return true;
                                    }
                                    var date1 = new Date(iValue);
                                    var date2 = new Date(iMax);
                                    var result = date1 - date2;
                                    //console.log(date1 + '-' + date2 + '= ' + result);

                                    //var f=dates.compare(iValue,iMax);
                                    if (result === 0) {
                                        return true;
                                    } else if (result < 0) {
                                        return true;
                                    }
                                    return false;


                                }
                        );
                        //Update table
                        oTable.fnDraw();
                        if (oTable.fnSettings().fnRecordsDisplay() === 0) {
                            //console.log('here ');
                            // $('id-row-count').value();
                            $('#id-row-count').textContent = 'The cross and its parents have matches from the database but does not match the specified date in the list. \n\
                                                                              Choose from the matches or Create a new GID.';
                            $('#id-create-new').show();
                            $('#id-submit').hide();
                            // $('#id-create-submit').hide();


                        } else {
                            $('#id-create-new').show();
                            $('#id-submit').show();
                            // $('#id-create-submit').show();
                        }

                        //Deleting the filtering funtion if we need the original table later.
                    }
                    $(document).ready(function() {
                        var pop = function() {
                            $('#choose-frm').hide();
                            $('#screen').css({opacity: 0.4, 'width': $(document).width(), 'height': $(document).height()});
                            $('body').css({'overflow': 'hidden'});
                            $('#ajax-loading-indicator').css({'display': 'block'});
                        }
                        $('#submit').click(pop);
                        /* Initialise datatables */

                        $('.open-modal').click(function() {
                            alert('hey');
                            var term = $(this).data('id');
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
                                url: '/GMGR/index.php?r=site/diagram',
                                data: {termId: term, arr_terms: m_values},
                                success: function(data) {
                                    $('#modal-pedTree').html(data);

                                }
                            });
                        });




                    });

                    function change() {
                        var cross = "<?php echo $cross; ?>";
                        var term = "<?php echo $m_term; ?>";
                        var cdate = "<?php echo $existing[$index][13]; ?>";
                        var elem = document.getElementById('filter');
                        if (cross === term) {
                            if (elem.value == 'Show Germplasm that has date ' + cdate) {
                                elem.value = 'Show All';
                                iMax = $('#sToId').attr('value');
                                $('#createNew').val("<?php echo $cross; ?>");
                                // $('#id-submit').val("<input class='btn btn-primary' type='submit' value='Assign' id='submit'> <a href='#' class='btn' data-dismiss='modal'>Cancel</a>");
                            } else {
                                elem.value = 'Show Germplasm that has date ' + cdate;
                                iMax = '';

                            }
                        } else {
                            if (elem.value == 'Show Germplasm created before ' + cdate) {
                                elem.value = 'Show All';
                                iMax = $('#sToId').attr('value');
                            } else {
                                elem.value = 'Show Germplasm created before ' + cdate;
                                iMax = '';
                            }
                        }
                        $.fn.dataTableExt.afnFiltering.push(
                                function(oSettings, aData, iDataIndex) {
                                    // 'date-range' is the id for my input
                                    var dateRange = $('#sToId').attr('value');

                                    // parse the range from a single field into min and max, remove ' - '
                                    iMin = '';

                                    // 4 here is the column where my dates are.
                                    var iValue = aData[1];

                                    if (iMax == '') {
                                        return true;
                                    }
                                    var date1 = new Date(iValue);
                                    var date2 = new Date(iMax);
                                    var result = date1 - date2;
                                    //console.log(date1 + '-' + date2 + '= ' + result);

                                    //var f=dates.compare(iValue,iMax);
                                    if (result === 0) {
                                        return true;
                                    } else if (result < 0) {
                                        return true;
                                    }
                                    return false;


                                }
                        );
                        //Update table
                        oTable.fnDraw();
                        if (oTable.fnSettings().fnRecordsDisplay() === 0) {
                            //console.log('here ');
                            // $('id-row-count').value();
                            $('#id-row-count').textContent = 'The cross and its parents have matches from the database but does not match the specified date in the list. \n\
                                                                              Choose from the matches or Create a new GID.';
                            $('#id-create-new').show();
                            $('#id-submit').hide();
                            //$('#id-create-submit').hide();

                        } else {
                            $('#id-create-new').show();
                            $('#id-submit').show();
                            // $('#id-create-submit').show();
                        }

                        //Deleting the filtering funtion if we need the original table later.
                    }
                    //************For opening a modal dialog***************
                    /*$(document).on('click', '.open-modal', function() {
                     //*****the term to be placed on the heading in the modal
                     alert('hey');
                     });*/
    </script>    