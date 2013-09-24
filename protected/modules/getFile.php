<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/PedigreeImport/model/configDB.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/PedigreeImport/model/json.class.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/PedigreeImport/model/file_toArray.class.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/PedigreeImport/model/curl.class.php");

include("top.txt");
include("breadcrumb_glist.txt");
include("methods.txt");

if (!$model->CheckLogin()) {
    $model->RedirectToURL("login.php");
    exit;
}

//json file of the locationID
$json = new json($_POST['location']);
$json->location();
$json->getFile();

//call curl: function parse
$curl = new curl();
$curl->parse();

// array from file output.csv
$file_toArray = new file_toArray();
$rows = $file_toArray->csv_output();
/*
  if(isset($_FILES['file']['name'])){

  print_r($_POST['location']);
  $tmpName = $_FILES['file']['tmp_name'];
  $newName =getcwd().FILES.$_FILES['file']['name'];
  //echo "tmpname".$tmpName."<br>";
  //echo "newname".$newName;
  if(!is_uploaded_file($tmpName) || !move_uploaded_file($tmpName, $newName)){
  echo "FAILED TO UPLOAD " . $_FILES['file']['name'] .
  "<br>Temporary Name: $tmpName <br>";
  } else {
  //save_document_info_json($_FILES['file']);
  }

  save_document_info_json();
  }elseif(isset($_POST['checked'])){
  print_r($_POST['checked']);
  save_json_checkedbox($_POST['checked']);
  }
  else {
  echo "Error.";
  } */
?>	  
<div id="GermplasmList">
    <h3>Germplasm List</h3>
    <p >
        <i><strong>Note:</strong>&nbsp; 
            Germplasm names <b>not</b> in <b>standardized</b> format are in <b>red color</b>.
        </i>
        <br><br>
        <a class='btn btn-primary' href="/PedigreeImport/standardized.php#GermplasmList">Click to Standardize Germplasm</a>
    </p>

    <table name="GermplasmList" class="table table-hover">
        <tbody>
        <thead>
            <tr>
                <th>Cross Name</th>
                <th>GID</th>
                <th>Female Parent</th>
                <th>Male Parent</th>
                <th>New GID</th>
            </tr>
        </thead>
        <?php
        foreach ($rows as $row) : list($GID, $nval, $female, $fid, $fremarks, $fgid, $male, $mid, $mremarks, $mgid) = $row;
            ?> <tr> <?php
                echo '<td>' . $nval . '</td>';
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
                    echo '<td title=' . $fremarks . '><font style="color:rgb(255, 0, 0); font-weight:bold;">' . $female . '</font></td>';
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
                    echo '<td title=' . $mremarks . '><font style="color:rgb(255, 0, 0); font-weight:bold;">' . $male . '</font></td>';
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
                    <pre><?php echo $fgid; ?></pre>
                    <?php
                    $your_array = array();
                    $your_array = explode("#", $mgid);
                    //print_r($your_array);
                    $your_array = implode("\n", $your_array);
                    //echo $your_array."<br>";
                    $mgid = $your_array;
                    ?>
                    <pre><?php echo $mgid; ?></pre>
                </td>
            </tr> 
        <?php endforeach; ?> 
        </tbody> 
    </table>
</div>	
<!-- END CONTENT -->

<?php
//include("tree_list.txt");
include("bottom.txt");
?>

