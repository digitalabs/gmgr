<?php
include_once(dirname(__FILE__) . "/../protected/modules/file_toArray.php");
$file_toArray = new file_toArray();

//Get existing terms
$isCsvExists = file_exists(dirname(__FILE__) . "/../csv_files/existingTerm.csv");

if ($isCsvExists) {
    $existing = $file_toArray->csv_existingTerm();
} else {
    //modal will never be displayed
}
?>

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
}
?>
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
            if (isset($existing) && ($isCsvExists)) {
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
<script type="text/javascript">
            //function for the loading indicator
            $(document).ready(function() {
                var pop = function() {
                    $('#form-content').css({'z-index': '1000'});
                    $('#screen').css({opacity: 0.4, 'width': $(document).width(), 'height': $(document).height()});
                    $('body').css({'overflow': 'hidden'});
                    $('#ajax-loading-indicator').css({'display': 'block'});

                }
                $('#submit').click(pop);

                $("input#submit").click(function() {
                    $.ajax({
                        type: "POST",
                        data: $('form.contact').serialize(),
                        beforeSend: function() {
                            $("#new-Modal").modal('hide');
                            $('#wait').show();
                        },
                        success: function() {
                            $("#GermplasmList").submit();
                            $('#wait').hide();
                            $('#new-Modal').css({'z-index': '1000'});
                            document.location.reload();
                        },
                        error: function() {
                            alert("failure");
                        }
                    });
                    return false;
                });
            });
</script>    