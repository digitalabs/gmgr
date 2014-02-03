<script src="./modules_folder/js/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="./modules_folder/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="./modules_folder/js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>

<?php
include_once(dirname(__FILE__) . "/../protected/modules/file_toArray.php");
$file_toArray = new file_toArray();
?>
<form class="contact" name="contact" action="index.php?r=site/assignGID" method="POST" id="choose-frm">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">X</a>
        <?php
        if (isset($_POST['termId'])) {
            $m_term = $_POST['termId'];
        } else {
            $m_term = "";
        }
        ?>
        <h3>Assign GID for <font style="color:#08c;"><?php echo $m_term ?> </font></h3>
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
        //print_r($createdGID);
    }

    if (count($existing) !== 0) {
        // print_r($existing);
        ?>
        <div class="modal-body">
            <?php if ($existing[0][13] != "not specified") {
                ?>
                <input type="hidden"  value="<?php echo $existing[0][13]; ?>"  id="sToId" name="sToId">
                <input type="button" class="btn btn-primary" value="Show Germplasm older than the cross" id="filter" onclick="change()" >&nbsp;&nbsp;
                <br><br>
                <?php
            }
            ?>
            <table class="table table-hover" id="model">
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
                        /* echo "m_term: ".$m_term."<br>";
                          echo "existing[j][1]: ".$existing[$j][1]."<br>";
                          echo "m_id: ".$m_id."<br>";
                          echo "existing[j][0]: ".$existing[$j][0]."<br>";
                         */
                        if ($m_term === $existing[$j][11] && $existing[$j][0] === $m_id) {
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
                            echo '<input type="hidden" name="list" value="' . base64_encode(serialize($list)) . '" />';
                            echo '<input type="hidden" name="createdGID" value="' . base64_encode(serialize($createdGID)) . '" />';
                            echo '<input type="hidden" name="existing" value="' . base64_encode(serialize($existing)) . '" />';
                            echo '<input type="hidden" name="checked" value="' . base64_encode(serialize($checked)) . '" />';
                            echo '<input type="hidden" name="locationID" value="' . $locationID . '" />';
                            echo "</td>";
                            echo "<td>" . $existing[$j][6] . "</td>";
                            
                            /*
                             *   echo "<td>" . $existing[$j][6] . "</td>";
                             echo "<td>".CHtml::link('Show Pedigree Tree',array('site/diagram'),array("target"=>"_blank"))."</td>";
                             */

                            echo "<td>(" . $existing[$j][2] . ")&nbsp; " . $existing[$j][3] . "</td>";
                            echo "<td>(" . $existing[$j][4] . ")&nbsp; " . $existing[$j][5] . "</td>";
                            echo "<td>(" . $existing[$j][7] . ")&nbsp; " . $existing[$j][8] . "</td>";
                            echo "<td>(" . $existing[$j][9] . ")&nbsp;" . $existing[$j][10] . "</td>";
                            echo "<td>" . $existing[$j][12] . "</td>";
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div id="modal-pedTree" class="modal hide fade in" style="display: none; z-index:2000"></div>

        </div>
        <div class="modal-footer">
            <input class="btn btn-primary" type="submit" value="Assign" id="submit">

            <a href="#" class="btn" data-dismiss="modal">Cancel</a>

        </div>
    </form> 
    <?php
}
?>
<script type="text/javascript">
            //function for the loading indicator
            //************For opening a modal dialog***************

            $(document).ready(function() {
                var pop = function() {
                    $('#choose-frm').hide();
                    $('#screen').css({opacity: 0.4, 'width': $(document).width(), 'height': $(document).height()});
                    $('body').css({'overflow': 'hidden'});
                    $('#ajax-loading-indicator').css({'display': 'block'});
                }
                $('#submit').click(pop);
                /* Initialise datatables */

               /* $(".open-modal").click(function() {
                    alert("hey");
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
                        url:  '/GMGR/index.php?r=site/diagram',
                        data: {termId: term, arr_terms: m_values},
                        success: function(data) {
                            $("#modal-pedTree").html(data);

                        }
                    });
                });*/

                $('#model').dataTable({
                    "bPaginate": false,
                    "bSort": false,
                    "bSearchable": false
                }).columnFilter({sPlaceHolder: "head:after",
                    aoColumns: [{type: "none"},
                        {type: "text"},
                        {type: "text"},
                        {type: "text"},
                        {type: "text"},
                        {type: "text"},
                        {type: "text"},
                        {type: "text"}
                    ]

                });


            });
            function change() {
                var elem = document.getElementById("filter");
                if (elem.value == "Show Germplasm older than the cross") {
                    elem.value = "Show All";
                    iMax = $('#sToId').attr("value");
                } else {
                    elem.value = "Show Germplasm older than the cross";
                    iMax = "";
                }
                $.fn.dataTableExt.afnFiltering.push(
                        function(oSettings, aData, iDataIndex) {
                            // "date-range" is the id for my input
                            var dateRange = $('#sToId').attr("value");

                            // parse the range from a single field into min and max, remove " - "
                            iMin = "";

                            // 4 here is the column where my dates are.
                            var iValue = aData[1];

                            if (iMax == "") {
                                return true;
                            }
                            var date1 = new Date(iValue);
                            var date2 = new Date(iMax);
                            var result = date1 - date2;
                            //console.log(date1 + "-" + date2 + "= " + result);

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
                $('#model').dataTable().fnDraw();
                //Deleting the filtering funtion if we need the original table later.
            }
            //************For opening a modal dialog***************
            /*$(document).on("click", ".open-modal", function() {
             //*****the term to be placed on the heading in the modal
             alert("hey");
             });*/
</script>    