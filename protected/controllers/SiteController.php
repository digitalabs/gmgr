<script>

    function storeLocal1() {
        if ('localStorage' in window && window['localStorage'] !== null) {
            try {
                document.getElementById('location').value = localStorage.locationID;
                document.getElementById('list').value = localStorage.list;
                document.forms["importFileDisplay-rfrsh"].submit();
            } catch (e) {
            }
        } else {
            alert('Cannot store user preferences as your browser do not support local storage');
        }
    }
    function storeLocal2() {
        if ('localStorage' in window && window['localStorage'] !== null) {
            try {
                document.getElementById('location').value = localStorage.locationID;
                document.getElementById('checked').value = localStorage.checked;
                document.getElementById('existing').value = localStorage.existing;
                document.getElementById('createdGID').value = localStorage.createdGID;
                document.getElementById('list').value = localStorage.list;
                document.forms["importFileDisplay-rfrsh"].submit();
            } catch (e) {
            }
        } else {
            alert('Cannot store user preferences as your browser do not support local storage');
        }
    }
</script>
<?php

class SiteController extends Controller {

    public function actions() {
        return array(
// captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
// They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function actionLocalStore1() {
        $this->render('localStore1');
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
// renders the view file 'protected/views/site/index.php'
// using the default layout 'protected/views/layouts/main.php'
//* This is the action to handle external exceptions.
        $this->render('index');
    }

    /**
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;


// if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

// collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
// validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
// $this->redirect(Yii::app()->user->returnUrl);
                $this->redirect(array('/site/importer'));
            }
        }

// display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionEditor() {

        $model = new ImporterForm;

        if ($model->validate()) {
            if (isset($_POST['searchBtn'])) {
                Yii::import('application.modules.curl');
                /* $gid = $_POST['inputGID'];
                  $a = array('GID'=>$gid);
                  $data = json_encode($a);
                  $ch = curl_init();
                  //$ch =curl_init($url);
                  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                  'Content-Type: application/json',
                  'Content-Length: ' . strlen($data))
                  );
                  $result = curl_exec($ch);
                  echo $gid; */

                $curl = new curl();
                $arr = $curl->searchGID();
                print_r($arr);
                $this->redirect(array('/site/editor'));
            }
        }
        $this->render('editor');
    }

    public function actionImporter() {

        $model = new ImporterForm;

//Collect user input form
        if (isset($_POST['ImporterForm'])) {
            
        } else {
            $this->render('importer', array(
                'model' => $model,
            ));
        }
    }

    public function actionImportFileDisplay() {

        Yii::import('application.modules.file_toArray');
        Yii::import('application.modules.json');
        Yii::import('application.modules.curl');
        $arr = array();
        $filtersForm = new FilterPedigreeForm;

        if (isset($_POST['location'])) {
            echo "HERE 0.<br>";
            if (isset($_POST['refresh'])) {
                echo "HERE refresh.<br>";
                $locationID = $_POST['location'];
                $list = json_decode($_POST['list']);
            } else {
                echo "HERE flow.<br>";
                $locationID = $_POST['location'];

                $json = new json('');
                $output = $json->getFile();

//call curl: function parse
                $curl = new curl();
                $list = $curl->parse($output);
            }
            $id = $list;

            foreach ($id as $row) :
                list($GID, $nval, $female, $fid, $fremarks, $fgid, $male, $mid, $mremarks, $mgid) = $row;
                $arr[] = array('id' => CJSON::encode(array($fid, $mid)), 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks);
            endforeach;

            if (isset($_GET['FilterPedigreeForm'])) {
                $filtersForm->filters = $_GET['FilterPedigreeForm'];
            }
            $filteredData = $filtersForm->filter($arr);
            $dataProvider = new CArrayDataProvider($filteredData, array(
                'pagination' => array(
                    'pageSize' => 10,
                ),
            ));

            if (!isset($_GET['ajax'])) {
                echo "HERE.<br>";
                $this->render('importFileDisplay', array(
                    'filtersForm' => $filtersForm,
                    'dataProvider' => $dataProvider,
                    'locationID' => $locationID,
                    'list' => $list
                ));
            } else {
                echo "ajax.<br>";
                $this->render('importFileDisplay', array(
                    'filtersForm' => $filtersForm,
                    'dataProvider' => $dataProvider,
                    'locationID' => $locationID,
                    'list' => $list
                ));
            }
        } else {
            ?>
            <html>
                <body onload="storeLocal1()">
                    <form action="index.php?r=site/importFileDisplay" method="post" id='importFileDisplay-rfrsh'>
                        <input type="hidden" name="refresh" value="true">
                        <input type="hidden" id ="location" name="location" value="">
                        <input type="hidden" id="list" name="list" value="">
                    </form>  
                </body>
            </html>

            <?php
        }
    }

    public function actionOutput() {

        $filtersForm = new FilterPedigreeForm;
        Yii::import('application.modules.curl');
        $curl = new curl();

        if (isset($_POST['locationID']) || isset($_POST['location'])) {
            if (isset($_POST['refresh'])) {
                $locationID = $_POST['location'];
                $list = json_decode($_POST['list']);
            } else {
                $data = $_POST['list'];
                $locationID = $_POST['locationID'];

                echo "<br>2locationID: " . $locationID;

                $data = json_decode($data, true);

                $a = array(
                    'list' => $data
                );

                $data = json_encode($a);

                $list = $curl->standardize($data);
            }

            foreach ($list as $row) :
                //list($GID, $nval, $female, $fid, $fremarks, $fgid, $male, $mid, $mremarks, $mgid) = $row;
                list($GID, $nval, $fid, $fremarks, $fgid, $female, $mid, $mremarks, $mgid, $male) = $row;
                $arr[] = array('id' => CJSON::encode(array($fid)), 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks);
            endforeach;


            if (isset($_GET['FilterPedigreeForm']))
                $filtersForm->filters = $_GET['FilterPedigreeForm'];

//get array data and create dataProvider
            $filteredData = $filtersForm->filter($arr);
            $dataProvider = new CArrayDataProvider($filteredData, array(
                'pagination' => array(
                    'pageSize' => 5,
                ),
                    )
            );

            if (!isset($_GET['ajax'])) {
                echo "HERE.<br>";
                $this->render('output', array(
                    'filtersForm' => $filtersForm,
                    'dataProvider' => $dataProvider,
                    'locationID' => $locationID,
                    'list' => $list
                ));
            } else {
                echo "ajax.<br>";
                $this->render('output', array(
                    'filtersForm' => $filtersForm,
                    'dataProvider' => $dataProvider,
                    'locationID' => $locationID,
                    'list' => $list
                ));
            }
        } else {
            ?>
            <html>
                <body onload="storeLocal1()">
                    <form action="index.php?r=site/output" method="post" id='importFileDisplay-rfrsh'>
                        <input type="hidden" name="refresh" value="true">
                        <input type="hidden" id ="location" name="location" value="">
                        <input type="hidden" id="list" name="list" value="">
                    </form>  
                </body>
            </html>

            <?php
        }
    }

    public function actionStandardTable() {

        $filtersForm = new FilterPedigreeForm;

        Yii::import('application.modules.file_toArray');
        $file_toArray = new file_toArray();
        $rows = $file_toArray->csv_corrected();

        foreach ($rows as $i => $row) :
//foreach ($rows as $row) :
            list($GID, $nval, $fid, $fremarks, $fgid, $female, $mid, $mremarks, $mgid, $male) = $row;

            CHtml::hiddenField('hiddenMid', $mid);
            CHtml::hiddenField('hiddenFid', $fid);
            /* For reference, pls do not delete
             * developer: J.Antonio */
// $arr[] = array('id'=>CJSON::encode(array('nval'=>$nval,'gid'=>$GID,'female'=>$female,'male'=>$male,'fgid'=>$fgid,'mgid'=>$mgid,'fremarks'=>$fremarks,'mremarks'=>$mremarks)),'nval'=>$nval,'gid'=>$GID,'female'=>$female,'male'=>$male,'fgid'=>$fgid,'mgid'=>$mgid,'fremarks'=>$fremarks,'mremarks'=>$mremarks);
            $arr[] = array('id' => CJSON::encode(array($fid)), 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks);

//$arr[] = array('id'=>$i+1,'nval'=>$nval,'gid'=>$GID,'female'=>$female,'male'=>$male,'mgid'=>$mgid,'fremarks'=>$fremarks);
// $arr[] = array('id'=>$i+1,'nval'=>$nval,'gid'=>$GID,'female'=>$female,'male'=>$male,'mgid'=>$mgid,'fgid'=>$fgid,'fremarks'=>$fremarks,'mremarks'=>$mremarks);
        endforeach;


        if (isset($_GET['FilterPedigreeForm']))
            $filtersForm->filters = $_GET['FilterPedigreeForm'];

//get array data and create dataProvider
        $filteredData = $filtersForm->filter($arr);
        $dataProvider = new CArrayDataProvider($filteredData, array(
            'pagination' => array(
                'pageSize' => 5,
            ),
                )
        );

//render
        $this->render('standardTable', array(
            'filtersForm' => $filtersForm,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionEditGermplasm() {

        $model = new editGermplasmForm;

        if (isset($_POST['editGermplasmForm'])) {
            $model->attributes = $_POST['editGermplasmForm'];
            if ($model->validate()) {
                $newGermplasmName = $_POST["editGermplasmForm"]['newGermplasmName']; //Gets the Input 
//echo $newGermplasmName;
                // $this->actionSaveGermplasm($newGermplasmName);
//  Yii::app()->user->setFlash('editGermplasmForm','Submitted');
            }
        }
        $this->render('editGermplasm', array('model' => $model));
    }

    public function actionSaveGermplasm() {

//<!---*******Notifications for any page changes******-->
        Yii::app()->user->setFlash('success', array('title' => 'Edit Successful!', 'text' => 'You successfully edited parent.'));
//<!----*******************************************-->
        $this->renderPartial('savegermplasm');
    }

    public function actionSampleAction() {
        if (isset($_POST['Germplasm']['gid'])) {
            if (!empty($_POST['Germplasm']['gid']))
                $selected = $_POST['Germplasm']['gid'];
            var_dump($selected);
        }

        $this->render('newFile', array(
            'selected' => $selected,
        ));
    }

    public function actionCreatedGID() {
        $filtersForm = new FilterPedigreeForm;
        if (isset($_POST["refresh"])) {
            $list = json_decode($_POST['list']);

            $rows = $list;
            /* If we have an array with items */
            if (count($rows)) {
                foreach ($rows as $i => $row) : list($GID, $nval, $fid, $fremarks, $fgid, $female, $mid, $mremarks, $mgid, $male) = $row;
                    $arr2[] = array('id' => $i + 1, 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks);

                endforeach;
            }

            if (isset($_GET['FilterPedigreeForm']))
                $filtersForm->filters = $_GET['FilterPedigreeForm'];

//get array data and create dataProvider
            $filteredData = $filtersForm->filter($arr2);
            /* DataProvider for the lower table, Germplasm List */
            $GdataProvider = new CArrayDataProvider($filteredData, array(
                'keyField' => 'id',
                'pagination' => array(
                    'pageSize' => 5,
                ),
            ));

//render page with ajax   
            if (Yii::app()->request->isAjaxRequest)
                $this->renderPartial('createdGID', array('filtersForm' => $filtersForm, 'GdataProvider' => $GdataProvider), false, true);
            else
                $this->renderPartial('createdGID', array('filtersForm' => $filtersForm, 'GdataProvider' => $GdataProvider));
        }else {
            ?>
            <html>
                <body onload="storeLocal1()">
                    <form action="index.php?r=site/output" method="post" id='importFileDisplay-rfrsh'>
                        <input type="hidden" name="refresh" value="true">
                        <input type="hidden" id ="location" name="location" value="">
                        <input type="hidden" id="list" name="list" value="">
                    </form>  
                </body>
            </html>
            <?php
        }
    }

    public function actionAssignGID() {

        Yii::import('application.modules.file_toArray');
        Yii::import('application.modules.json');
        Yii::import('application.modules.curl');
        $file_toArray = new file_toArray();
        $curl = new curl();

        $arrSelectedIds = array();
        $filtersForm = new FilterPedigreeForm;
        if (isset($_POST['locationID']) || isset($_POST['location'])) {
            if (isset($_POST['Germplasm']['gid']) && ($_POST['Germplasm']['gid'] != '')) {

                if (!empty($_POST['Germplasm']['gid'])) {
                    $selected = $_POST['Germplasm']['gid'];
                    $idArr = explode(',', $selected);
                    foreach ($idArr as $index => $id) {
                        $id = strtr($id, array('["' => '', '"]' => ''));
                        $arrSelectedIds[$index] = ($id);
                    }
                }
//call curl: function standardization

                $data = $_POST['list'];
                $locationID = $_POST['locationID'];
                $list = json_decode($data, true);
                $checked = $arrSelectedIds;
                $standardized = $file_toArray->checkIf_standardize($checked, $list);

                $a = array(
                    'list' => $list,
                    'checked' => $checked,
                    'existingTerm' => array(),
                    'locationID' => $locationID,
                    'userID' => Yii::app()->user->id
                );

                $data = json_encode($a);
                $output = $curl->createGID($data);

                $createdGID = array();
                $list = array();
                $createdGID = $output['createdGID'];
                $list = $output['list'];
                $existing = $output['existingTerm'];

                $rows = $createdGID;
            } elseif (isset($_POST['choose'])) {

                $term = strip_tags($_POST['term']);
                $pedigree = strip_tags($_POST['pedigree']);
                $id = strip_tags($_POST['id']);
                $choose = strip_tags($_POST['choose']);
                $fid = strip_tags($_POST['fid']);
                $mid = strip_tags($_POST['mid']);
                $female = strip_tags($_POST['female']);
                $male = strip_tags($_POST['male']);
                $locationID = strip_tags($_POST['locationID']);

                $list = unserialize(base64_decode($_POST['list']));
                $createdGID = unserialize(base64_decode($_POST['createdGID']));
                $existing = unserialize(base64_decode($_POST['existing']));
                $checked = unserialize(base64_decode($_POST['checked']));

                $userID = Yii::app()->user->id;
                $output = $file_toArray->updateGID_createdGID($term, $pedigree, $id, $choose, $fid, $mid, $female, $male, $createdGID, $existing, $list, $userID);
                $output = $curl->chooseGID(json_encode($output));

                $createdGID = array();
                $list = array();
                $createdGID = $output['createdGID'];
                $list = $output['list'];
                $rows = $createdGID;
// update corrected.csv
//$file_toArray->update_csv_correctedGID($fid, $mid, $checked);
            } elseif (isset($_POST['selectMethod']) && $_POST['selectMethod'] != 0) {
                $arrayStringMethod = $_POST['selectMethod'];
                $locationID = $_POST['locationID'];

                $list = unserialize(base64_decode($_POST['list']));
                $createdGID = unserialize(base64_decode($_POST['createdGID']));
                $existing = unserialize(base64_decode($_POST['existing']));
                $checked = unserialize(base64_decode($_POST['checked']));

                $arr = explode(",", $arrayStringMethod);
                $mid = $arr[0];
                $gid = $arr[1];
                $id = $arr[2];

//*****if the user selects the radio button with the typeahead*****
                $array = array(
                    "mid" => $mid,
                    "gid" => $gid,
                    "id" => $id,
                    "createdGID" => $createdGID
                );
                $curl = new curl();
                $output = $curl->updateMethod(json_encode($array));
                $createdGID = $output['createdGID'];
                $rows = $createdGID;
            } else {
                $locationID = $_POST['location'];
                $rows = json_decode($_POST['createdGID']);
                $existing = json_decode($_POST['existing']);
                $list = json_decode($_POST['list']);
                $checked = json_decode($_POST['checked']);
                $createdGID = $rows;
            }
            if (count($rows)) {
                foreach ($rows as $i => $row) : list($GID, $nval, $fid, $fremarks, $fgid, $female, $mid, $mremarks, $mgid, $male) = $row;
                    $arr2[] = array('id' => $i + 1, 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks);

                endforeach;
            }
            if (isset($_GET['FilterPedigreeForm']))
                $filtersForm->filters = $_GET['FilterPedigreeForm'];

//get array data and create dataProvider
            $filteredData = $filtersForm->filter($arr2);
//DataProvider for the lower table, Germplasm List
            $GdataProvider = new CArrayDataProvider($filteredData, array(
                'keyField' => 'id',
                'pagination' => array(
                    'pageSize' => 5,
                ),
            ));

            $this->render('assignGID', array('filtersForm' => $filtersForm,
                'checked' => $checked, 'GdataProvider' => $GdataProvider,
                'locationID' => $locationID,
                'list' => $list,
                'existing' => $existing,
                'createdGID' => $createdGID
            ));
        }else {
            ?>
            <html>
                <body onload="storeLocal2()">
                    <form action="index.php?r=site/assignGID" method="post" id='importFileDisplay-rfrsh'>
                        <input type="hidden" name="refresh" value="true">
                        <input type="hidden" id ="location" name="location" value="">
                        <input type="hidden" id="list" name="list" value="">
                        <input type="hidden" id="existing" name="existing" value="">
                        <input type="hidden" id="createdGID" name="createdGID" value="">
                        <input type="hidden" id="checked" name="checked" value="">
                    </form>  
                </body>
            </html>
            <?php
        }
    }

    public function actionChooseGID() {
        $this->render('chooseGID');
    }

    public function actionContactUs() {
        $this->render('contactUs');
    }

}

