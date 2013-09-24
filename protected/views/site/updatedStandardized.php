<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/PedigreeImport/model/configDB.php");

if (!$model->CheckLogin()) {
    $model->RedirectToURL("login.php");
    exit;
}
include("top.txt");
include("breadcrumb_glist.txt");
include("methods.txt");
define("FILES", '\\');

if (isset($_POST['checked'])) {
    $checked = $_POST['checked'];
    $fid = $_POST['fid'];
    $mid = $_POST['mid'];
    //print_r($_POST['checked']);

    save_json_checkedbox($_POST['checked']);
    updateCorrectedFile($fid, $mid, $checked);

    
    $fp = fopen("corrected.csv", "r");
    $rows = array();
    while (($row = fgetcsv($fp)) !== FALSE) {
        $rows[] = $row;
    }
    fclose($fp);
}
?>

<div id="GermplasmList">
    <h3>Germplasm List</h3>
    <i><p><strong>Note:</strong>&nbsp; 
        Germplasm names not in standardized format are in red color.Hover the mouse over the germplasm names to see the error and click to correct it.
    </p>
    </i>
<?php
$fp = fopen("corrected.csv", "r");
$rows = array();
while (($row = fgetcsv($fp)) !== FALSE) {
    $rows[] = $row;
}
fclose($fp);
?>


    <form action="showGID.php" method="post">
<?php
echo '<table name="GermplasmList" class="table table-hover">';
echo "<tbody>
<thead>
						<tr>
						<th>Cross Name</th>
						<th>GID</th>
						<th>Female Parent</th>
						<th>Male Parent</th>
                        <th>New GID</th>
        </tr>
 </thead>";

?>
        

<?php
$termArray = array();
$i = 0;
foreach ($rows as $row) : list($GID, $nval, $fid, $fremarks, $fgid, $female, $mid, $mremarks, $mgid, $male) = $row;

    echo '<tr> ';

    echo '<td><input type="checkbox" name="checked[]" value="' . $fid . '" >' . $nval . '</td>';
    echo "<input type='hidden' name='fid' value='.$fid.'/>";
    echo "<input type='hidden' name='mid' value='.$mid.'/>";

    echo '<td>' . $GID . '</td> ';
    if (strcmp($fremarks, 'in standardized format') == 0) {
        echo '<td title=' . $fremarks . '>' . $female . '</td>';
    } else {
        $your_array = array();
        $your_array = explode("#", $fremarks);
        //print_r($your_array);
        $your_array = implode("\n", $your_array);
        //echo $your_array."<br>";
        $fremarks = $your_array;
        echo '<td title=' . $fremarks . '><a style="color:rgb(255, 0, 0); font-weight:bold;" href="/PedigreeImport/editGermplasm.php?germplasm=' . $female . '&error=' . $fremarks . '">' . $female . '<a></td>';
    }
    if (strcmp($mremarks, 'in standardized format') == 0) {
        echo '<td title=' . $mremarks . '>' . $male . '</td>';
    } else {
        $your_array = array();
        $your_array = explode("#", $mremarks);
        //print_r($your_array);
        $your_array = implode("\n", $your_array);
        //echo $your_array."<br>";
        $mremarks = $your_array;
        echo '<td title=' . $mremarks . '><a style="color:rgb(255, 0, 0); font-weight:bold;" href="/PedigreeImport/editGermplasm.php?germplasm=' . $male . '&error=' . $mremarks . '">' . $male . '<a></td>';
    }
    ?>
            <td>
            <?php
            $your_array = array();
            $your_array = explode("#", $fgid);
            //print_r($your_array);
            $your_array = implode("\n", $your_array);
            //echo $your_array."<br>";
            $fgid = $your_array;
            ?>
                <pre><?php echo $fgid; ?></pre><br> 
                <?php
                $your_array = array();
                $your_array = explode("#", $mgid);
                //print_r($your_array);
                $your_array = implode("\n", $your_array);
                //echo $your_array."<br>";
                $mgid = $your_array;
                echo '<pre>' . $mgid . '</pre>';
                for ($i = 0; $i < count($rows); $i++) {
                    if ($fid === $rows[$i][0]) {

                        echo "<a class='btn btn-primary' href='/PedigreeImport/createdGID.php?female=" . $female . "&male=" . $male . "&fid=" . $fid . "&mid=" . $mid . "'>Show Created GID</a>";
                        break;
                    }
                }
                ?>

            </td>

            </tr> 
<?php endforeach; ?> 
        </tbody> 
        </table>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Assign GID</button>
        </div>
    </form>	
</div>

<!-- END CONTENT -->

<?php
include("bottom.txt");
?>

