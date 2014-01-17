<html><body>
        <?php
        Yii::import('application.modules.curl');

//function save(){
        if (isset($_POST['newGermplasmName'])) {
            $germplasm = $_POST['germplasmName'];
            $new = $_POST['newGermplasmName'];
            $list_array = json_decode($_POST['list']);

            $output = callCurl($new, $list_array, $germplasm);
            //var_dump($output);
            echo "<br>";
            $list_array = $output['list'];
            print_r($list_array);
            echo "<br/><br/><br/>";
            print_r(json_encode($list_array));
            $updated = $output['updated'];
            if ($output['updated'] == true) {

                echo "output:" . $output['updated'];
                $error = $output['newString'][2];
                $gid = $output['newString'][3];
            } else {

                echo "output2:" . $output['updated'];
                $error = $output['newString'][2];
                $gid = $output['newString'][3];
                //echo "errrror: " . $error . "<br>";
                //echo "gid: " . $gid . "<br>";

                $your_array = array();
                $your_array = explode("#", $error);
                $your_array = implode("\n", $your_array);
                $error = $your_array;
            }
        } else {
            echo "Error";
        }
        echo "<input type='hidden' name='update' id='update' value='$updated'>";
        //echo "<input type='hidden' name='list' id='list' value='".$list_array."'>";
        echo CHtml::hiddenField('list1', json_encode($list_array));
        echo CHtml::hiddenField('error1', $error);
        echo CHtml::hiddenField('gid1', $gid);

//}
        function callCurl($new, $list_array, $old) {
            $a = array('new' => $new, 'list' => $list_array, 'old' => $old);
            $data = json_encode($a);
            $curl = new curl();
            $list = $curl->updateGermplasmName($data);

            return $list;
        }
        ?>
    </body></html>

<?php
$error = $output['newString'][2];
$gid = $output['newString'][3];
echo "errrror: " . $error . "<br>";
echo "gid: " . $gid . "<br>";

$your_array = array();
$your_array = explode("#", $error);
$your_array = implode("\n", $your_array);
$error = $your_array;
?>