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
    function storeLocal_process() {
        if ('localStorage' in window && window['localStorage'] !== null) {
            try {
                //   localStorage.removeItem("list");
                //  localStorage.removeItem("existing");

                //localStorage.removeItem("createdGID");
                document.getElementById('location').value = localStorage.locationID;
                document.getElementById('checked').value = localStorage.checked;
                document.getElementById('existing').value = localStorage.existing;
                document.getElementById('createdGID').value = localStorage.createdGID;
                document.getElementById('list').value = localStorage.list;
                document.forms["process-id"].submit();
            } catch (e) {
            }
        } else {
            alert('Cannot store user preferences as your browser do not support local storage');
        }
    }
    function storeLocal_refresh() {
        if ('localStorage' in window && window['localStorage'] !== null) {
            try {
                //   localStorage.removeItem("list");
                //  localStorage.removeItem("existing");

                //localStorage.removeItem("createdGID");
                console.log("existing: " + localStorage.existing);
                document.getElementById('location-refresh').value = localStorage.locationID;
                document.getElementById('checked-refresh').value = localStorage.checked;
                document.getElementById('existing-refresh').value = localStorage.existing;
                document.getElementById('createdGID-refresh').value = localStorage.createdGID;
                document.getElementById('list-refresh').value = localStorage.list;
                document.forms["refresh-id"].submit();
            } catch (e) {
            }
        } else {
            alert('Cannot store user preferences as your browser do not support local storage');
        }
    }
</script>
<?php

class SiteController extends Controller {

    public $browserSession = NULL;

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
        $dbFormModel = new databaseForm;
        $centralForm = new centralDBForm;

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
            } else {
                $this->redirect(Yii::app()->baseUrl);
            }
        }

        //****backend details
        if (isset($_POST['databaseForm']) && isset($_POST['centralDBForm'])) {

            // if ($dbFormModel->validate() && $centralDBForm->validate()) {
            //local database
            $local_db_host = $_POST['databaseForm']['host'];
            $local_db_name = $_POST['databaseForm']['database_name'];
            $local_db_port = $_POST['databaseForm']['port_name'];
            $local_db_username = $_POST['databaseForm']['database_username'];
            $local_db_password = $_POST['databaseForm']['database_password'];

            //central database
            $central_db_host = $_POST['centralDBForm']['host'];
            $central_db_name = $_POST['centralDBForm']['database_name'];
            $central_db_port = $_POST['centralDBForm']['port_name'];
            $central_db_username = $_POST['centralDBForm']['database_username'];
            $central_db_password = $_POST['centralDBForm']['database_password'];

            $database_details = array(
                'local_db_host' => $local_db_host,
                'local_db_name' => $local_db_name,
                'local_db_port' => $local_db_port,
                'local_db_username' => $local_db_username,
                'local_db_password' => $local_db_password,
                'central_host'=> $central_db_host,
                'central_db_name' => $central_db_name,
                'central_db_port' => $central_db_port,
                'central_db_username' => $central_db_username,
                'central_db_password' => $central_db_password    
            );
            $data = json_encode($database_details);

            // }
            $this->render('login', array('model' => $model, 'database_details' => $database_details));
        } else {
            //local database
            $local_db_host = '';
            $local_db_name = '';
            $local_db_port = '';
            $local_db_username = '';
            $local_db_password = '';


            //central database
            $central_db_host = '';
            $central_db_name = '';
            $central_db_port = '';
            $central_db_username = '';
            $central_db_password = '';

            $database_details = array(
                'local_db_host' => $local_db_host,
                'local_db_name' => $local_db_name,
                'local_db_port' => $local_db_port,
                'local_db_username' => $local_db_username,
                'local_db_password' => $local_db_password,
                'central_host'=> $central_db_host,
                'central_db_name' => $central_db_name,
                'central_db_port' => $central_db_port,
                'central_db_username' => $central_db_username,
                'central_db_password' => $central_db_password    
            );
            $this->render('login', array('model' => $model, 'database_details' => $database_details));
        }
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
        Yii::import('application.modules.curl');

        if (isset($_POST['searchBtn']) || isset($_POST['updateBtn'])) {

            $curl = new curl();
            $arr = $curl->searchGID();

            $tree = $arr['tree'];
            $found = $arr['found'];

            if ($found === "1") {
                echo "<script type='text/javascript'>alert('Search returned 0 results. ');</script>";
            }

            $out = json_decode($tree);

            $Data = $tree;
            $File = dirname(__FILE__) . '/../../json_files/treePHP.json';

            $Handle = fopen($File, 'w');

            fwrite($Handle, $tree);
            print "Data Written";
            fclose($Handle);
        }

        $this->render('editor');
    }

    public function actionViewDiagram() {

        $model = new ImporterForm;
        Yii::import('application.modules.curl');

        if (isset($_GET['inputGID']) && isset($_GET['maxStep'])) {
            $in = $_GET['inputGID'];
            $max = $_GET['maxStep'];
            $curl = new curl();
            $arr = $curl->showDiagram($in, $max);

            $tree = $arr['tree'];


            $out = json_decode($tree);

            $Data = $tree;
            $File = dirname(__FILE__) . '/../../json_files/diagram.json';

            $Handle = fopen($File, 'w');

            fwrite($Handle, $tree);
            print "Data Written";
            fclose($Handle);

            $this->redirect(array('/site/viewDiagram'));
        }

        $this->render('viewDiagram');
    }

    public function actionImporter() {

        $model = new ImporterForm;
        Yii::app()->session['username'] = Yii::app()->user->id;
        $this->browserSession = Yii::app()->session['username'];
        $model2 = new LoginForm;
        $dir = dirname(__FILE__) . '/../../uploadedFiles/';

        if (isset($this->browserSession)) {

            $model = new ImporterForm;
            $dbFormModel = new databaseForm;
            $centralDBForm = new centralDBForm;

            $uploaded = false;

            //Collect user input form
            if (isset($_POST['ImporterForm'])) {
                //$importedFile->attributes = $_POST['ImporterForm'];
                if ($importedFile->validate()) {
                    //  if(!empty($_FILES['ImporterForm']['file'])){
                    $file = CUploadedFile::getInstance($importedFile, 'file');

                    static $filePath;

                    $importedFile->file = $file;
                    $filePath = $dir . '/' . $importedFile->file;
                }
            } else {
                $this->render('importer', array(
                    'model' => $model,
                    'dbFormModel' => $dbFormModel,
                    'centralDBForm' => $centralDBForm,
                ));
            }
        } else {
            $this->render('login', array('model' => $model2));
        }
    }

    public function actionImportFileDisplay() {

        Yii::import('application.modules.file_toArray');
        Yii::import('application.modules.json');
        Yii::import('application.modules.curl');
        $arr = array();
        $filtersForm = new FilterPedigreeForm;

        Yii::app()->session['username'] = Yii::app()->user->id;
        $this->browserSession = Yii::app()->session['username'];
        $model2 = new LoginForm;
        $dir = dirname(__FILE__) . '/../../uploadedFiles';
        $newName = "germplasmFile.csv";

        $importedFile = new ImporterForm;


        if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($this->browserSession)) {

                if (isset($_POST['ImporterForm'])) {
                    //print_r($_POST['ImporterForm']);
                    $importedFile->attributes = $_POST['ImporterForm'];
                    if ($importedFile->validate()) {
                        //  if(!empty($_FILES['ImporterForm']['file'])){
                        $file = CUploadedFile::getInstance($importedFile, 'file');

                        static $filePath;

                        $importedFile->file = $file;
                        $filePath = $dir . '/' . $importedFile->file;

                        if (file_exists($newName)) {
                            //  unlink($dir . '/' . $newName);
                        }
                        //***check if file is not null
                        if (isset($file)) {
                            $file = $importedFile->file;
                            $importedFile->file->saveAs($dir . '/' . $file);
                            rename($filePath, $dir . '/' . $newName);
                        }
                        $newFilename = $dir . '/' . $newName;

                        if (isset($_POST['location'])) {
                            $location = $_POST['location'];

                            if (isset($_POST['refresh'])) {

                                $location = $_POST['location'];
                                $locationID = $location;
                                $list = unserialize(base64_decode($_POST['list']));
                            } else {
                                //echo "no refresh";
                                $location = $_POST['location'];
                                $locationID = $location;
                                $json = new json('');
                                $output = $json->getFile($newFilename);
                                $curl = new curl();
                                $list = $curl->parse($output);
                            }
                            $id = $list;


                            foreach ($id as $row) :

                                list($GID, $nval, $fid, $fremarks, $fgid, $female, $mid, $mremarks, $mgid, $male, $date) = $row;
                                $arr[] = array('id' => CJSON::encode(array($fid, $mid)), 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks, 'date' => $date);
                            endforeach;

                            if (isset($_GET['FilterPedigreeForm'])) {
                                $filtersForm->filters = $_GET['FilterPedigreeForm'];
                            }
                            $filteredData = $filtersForm->filter($arr);
                            $dataProvider = new CArrayDataProvider($filteredData, array(
                                'pagination' => array(
                                    'pageSize' => 5,
                                ),
                            ));

                            $this->render('importFileDisplay', array(
                                'filtersForm' => $filtersForm,
                                'dataProvider' => $dataProvider,
                                'locationID' => $locationID,
                                'list' => $list
                            ));
                        }
                    } else {
                        $this->render('importer', array('model' => $importedFile));
                    }
                } elseif (isset($_POST['next']) || isset($_POST['refresh'])) {

                    if (isset($_POST['location'])) {
                        $location = $_POST['location'];

                        if (isset($_POST['next']) || isset($_POST['refresh'])) {

                            $location = $_POST['location'];
                            $locationID = $location;
                            $list = unserialize(base64_decode($_POST['list']));
                        }
                        $id = $list;
                        foreach ($id as $row) :
                            //list($GID, $nval, $fid, $fremarks, $fgid, $female, $mid, $mremarks, $mgid, $male, $date) = $row;
                            list($GID, $nval, $fid, $fremarks, $fgid, $female, $mid, $mremarks, $mgid, $male, $date) = $row;
                            $arr[] = array('id' => CJSON::encode(array($fid)), 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks, 'date' => $date);
                        endforeach;


                        if (isset($_GET['FilterPedigreeForm'])) {
                            $filtersForm->filters = $_GET['FilterPedigreeForm'];
                        }
                        $filteredData = $filtersForm->filter($arr);
                        $dataProvider = new CArrayDataProvider($filteredData, array(
                            'pagination' => array(
                                'pageSize' => 5,
                            ),
                        ));

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
        } else {
            $this->render('login', array('model' => $model2));
        }
    }

    public function actionOutput() {

        $filtersForm = new FilterPedigreeForm;
        $filtersForm2 = new FilterPedigreeForm2;

        Yii::import('application.modules.curl');
        $curl = new curl();

        Yii::app()->session['username'] = Yii::app()->user->id;
        $this->browserSession = Yii::app()->session['username'];
        $model2 = new LoginForm;
        //static $locationID, $list;
        if (isset($this->browserSession)) {
            if (isset($_POST['locationID']) || isset($_POST['location'])) {
                //echo "<br>enter here"; 
                if (isset($_POST['next']) || isset($_POST['refresh'])) {
                    //echo "<br>Refresh!!!";
                    $locationID = $_POST['location'];
                    $list = unserialize(base64_decode($_POST['list']));
                    

                    //echo "<br>list:<br>";
                    //print_r($list);
                } else {
                    //echo "else here";
                    //$data = json_decode($_POST['list']);
                    $list = unserialize(base64_decode($_POST['list']));
                    $locationID = $_POST['locationID'];

                   // $data = json_decode($data, true);

                    $a = array(
                        'list' => $list
                    );

                    $data = json_encode($a);

                    $list = $curl->standardize($data);
                }
                foreach ($list as $row) :
                    //list($GID, $nval, $female, $fid, $fremarks, $fgid, $male, $mid, $mremarks, $mgid) = $row;
                    list($GID, $nval, $fid, $fremarks, $fgid, $female, $mid, $mremarks, $mgid, $male, $date) = $row;
                    $arr[] = array('id' => CJSON::encode(array($fid)), 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks, 'date' => $date);
                    if ((strcmp(($fremarks), "in standardized format")) != 0 || (strcmp(($mremarks), "in standardized format")) != 0) {
                        $nonStandardize[] = array('id' => CJSON::encode(array($fid)), 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks, 'date' => $date);
                    }
                endforeach;


                if (isset($_GET['FilterPedigreeForm'])) {
                    $filtersForm->filters = $_GET['FilterPedigreeForm'];
                }
                if (isset($nonStandardize)) {
                    if (isset($_GET['FilterPedigreeForm2'])) {
                        $filtersForm2->filters = $_GET['FilterPedigreeForm2'];
                    }
                    $notStandard = $filtersForm2->filter($nonStandardize);
                    $dataProvider2 = new CArrayDataProvider($notStandard, array(
                        'pagination' => array(
                            'pageSize' => 5,
                    )));
                    $not_standard_size = count($notStandard);
                    // echo "<br>this:" . $not_standard_size;
                }
                //get array data and create dataProvider
                $filteredData = $filtersForm->filter($arr);

                $dataProvider = new CArrayDataProvider($filteredData, array(
                    'pagination' => array(
                        'pageSize' => 5,
                )));

                //renders the data-browser
                if (isset($nonStandardize)) {
                    $this->render('output', array(
                        'filtersForm' => $filtersForm,
                        'filtersForm2' => $filtersForm2,
                        'dataProvider' => $dataProvider,
                        'dataProvider2' => $dataProvider2,
                        'nonstandardized_size' => $not_standard_size,
                        'locationID' => $locationID,
                        'list' => $list
                    ));
                } else {
                    $this->render('output', array(
                        'filtersForm' => $filtersForm,
                        'dataProvider' => $dataProvider,
                        'nonstandardized_size' => 0,
                        'locationID' => $locationID,
                        'list' => $list
                    ));
                }
            }

            //***This condition is loaded when refresh or browser-back is pressed
            else {
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
        } else {
            $this->render('login', array('model' => $model2));
        }
    }

    public function actionStandardTable() {

        $filtersForm = new FilterPedigreeForm;

        Yii::app()->session['username'] = Yii::app()->user->id;
        $this->browserSession = Yii::app()->session['username'];
        $model2 = new LoginForm;

        if (isset($this->browserSession)) {

            Yii::import('application.modules.file_toArray');
            $file_toArray = new file_toArray();
            $rows = $file_toArray->csv_corrected();

            foreach ($rows as $i => $row) :
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
        }else {
            $this->render('login', array('model' => $model2));
        }
    }

    public function actionEditGermplasm() {

        Yii::app()->session['username'] = Yii::app()->user->id;
        $this->browserSession = Yii::app()->session['username'];
        $model2 = new LoginForm;

        if (isset($this->browserSession)) {

            $model = new editGermplasmForm;
//print_r($model);
            if (isset($_POST['editGermplasmForm'])) {
                $model->attributes = $_POST['editGermplasmForm'];
                if ($model->validate()) {
                    $newGermplasmName = $_POST["editGermplasmForm"]['newGermplasmName']; //Gets the Input 
                }
            }
            $this->renderPartial('editGermplasm', array('model' => $model));
        } else {
            $this->render('login', array('model' => $model2));
        }
    }

    public function actionSaveGermplasm() {
        Yii::app()->session['username'] = Yii::app()->user->id;
        $this->browserSession = Yii::app()->session['username'];
        $model2 = new LoginForm;

        if (isset($this->browserSession)) {
//<!---*******Notifications for any page changes******-->
            Yii::app()->user->setFlash('success', array('title' => 'Edit Successful!', 'text' => 'You successfully edited parent.'));
//<!----*******************************************-->
            $this->renderPartial('savegermplasm');
        } else {
            $this->render('login', array('model' => $model2));
        }
    }

    public function actionCreatedGID() {
        Yii::app()->session['username'] = Yii::app()->user->id;
        $this->browserSession = Yii::app()->session['username'];
        $model2 = new LoginForm;

        if (isset($this->browserSession)) {
            $filtersForm = new FilterPedigreeForm;
            if (isset($_POST["refresh"])) {
                $list = json_decode($_POST['list']);

                $rows = $list;
                /* If we have an array with items */
                if (count($rows)) {
                    foreach ($rows as $i => $row) : list($GID, $nval, $fid, $fremarks, $fgid, $female, $mid, $mremarks, $mgid, $male, $date) = $row;
                        $arr2[] = array('id' => $i + 1, 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks, 'date' => $date);

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
            }elseif (isset($_GET['page'])) {
                ?>
                <html>
                    <body onload="storeLocal1()">
                        <form action="" method="post" id='importFileDisplay-rfrsh'>
                            <input type="hidden" name="refresh" value="true">
                            <input type="hidden" id ="location" name="location" value="">
                            <input type="hidden" id="list" name="list" value="">
                        </form>  
                    </body>
                </html>

                <?php
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
        } else {
            $this->render('login', array('model' => $model2));
        }
    }

    public function actionAssignGID() {
        Yii::app()->session['username'] = Yii::app()->user->id;
        $this->browserSession = Yii::app()->session['username'];
        $model2 = new LoginForm;

        if (isset($this->browserSession)) {
            Yii::import('application.modules.file_toArray');
            Yii::import('application.modules.json');
            Yii::import('application.modules.curl');
            Yii::import('application.modules.model');
            $file_toArray = new file_toArray();
            $curl = new curl();
            $model = new model();

            $arrSelectedIds = array();
            $filtersForm = new FilterPedigreeForm;
            if (isset($_POST['locationID']) || isset($_POST['location']) || isset($_POST['process'])) {

                if ((isset($_POST['Germplasm']['gid']) && ($_POST['Germplasm']['gid'] != '')) || isset($_POST['process'])) {
                    $data = $_POST['list'];

                    $list = json_decode($data, true);

                    if (!empty($_POST['Germplasm']['gid'])) {
                        //echo "here 0";
                        $selected = $_POST['Germplasm']['gid'];
                        $idArr = explode(',', $selected);
                        foreach ($idArr as $index => $id) {
                            $id = strtr($id, array('["' => '', '"]' => ''));
                            $arrSelectedIds[$index] = ($id);
                        }
                        $locationID = $_POST['locationID'];
                        $checked = $arrSelectedIds;
                        $standardized = $file_toArray->checkIf_standardize($checked, $list);

                        $checked = $standardized;

                        //database settings
                        $local_db_name = Yii::app()->request->getParam('local_db_name');
                        $local_db_port = Yii::app()->request->getParam('local_db_port');
                        $local_db_username = Yii::app()->request->getParam('local_db_username');
                        $central_db_name = Yii::app()->request->getParam('central_db_name');
                        $central_db_port = Yii::app()->request->getParam('central_db_port');
                        $central_db_username = Yii::app()->request->getParam('central_db_username');

                        $a = array(
                            'list' => $list,
                            'checked' => $standardized,
                            'existingTerm' => array(),
                            'locationID' => $locationID,
                            'userID' => Yii::app()->user->id,
                            'local_db_name' => $local_db_name,
                            'local_db_port' => $local_db_port,
                            'local_db_username' => $local_db_username,
                            'central_db_name' => $central_db_name,
                            'central_db_port' => $central_db_port,
                            'central_db_username' => $central_db_username
                        );

                        $data = json_encode($a);

                        $output = $curl->createGID($data);

                        $createdGID = array();
                        $list = array();
                        $createdGID = $output['createdGID'];
                        $list = $output['list'];
                        $existing = $output['existingTerm'];

                        $rows = $list;
                    } elseif (isset($_POST['process'])) {
                        //echo "here 1";
                        $createdGID = unserialize(base64_decode($_POST['createdGID']));
                        $existing = unserialize(base64_decode($_POST['existing']));
                        $list = unserialize(base64_decode($_POST['list']));
                        $checked = unserialize(base64_decode($_POST['checked']));

                        $locationID = $_POST['location'];
                        //$checked = json_decode($_POST['checked'], true);
                        //$createdGID = json_decode($_POST['createdGID'], true);
                        //$existing = json_decode($_POST['existing'], true);
                        $unselected = $file_toArray->get_unselected_rows($checked, $list);
                        $standardized = $file_toArray->checkIf_standardize($unselected, $list);
                        // echo "checked: ";
                        // print_r($checked);
                        $checked_all = array();
                        for ($i = 0; $i < count($checked); $i++) {
                            $checked_all[$i] = $checked[$i];
                        }
                        $j = count($checked_all);
                        for ($i = 0; $i < count($unselected); $i++) {
                            $checked_all[$j] = $unselected[$i];
                            $j++;
                        }
                        $checked = $standardized;

                        //database settings
                        $local_db_name = Yii::app()->request->getParam('local_db_name');
                        $local_db_port = Yii::app()->request->getParam('local_db_port');
                        $local_db_username = Yii::app()->request->getParam('local_db_username');
                        $central_db_name = Yii::app()->request->getParam('central_db_name');
                        $central_db_port = Yii::app()->request->getParam('central_db_port');
                        $central_db_username = Yii::app()->request->getParam('central_db_username');


                        $a = array(
                            'list' => $list,
                            'checked' => $checked,
                            'existingTerm' => $existing,
                            'createdGID' => $createdGID,
                            'locationID' => $locationID,
                            'userID' => Yii::app()->user->id,
                            'local_db_name' => $local_db_name,
                            'local_db_port' => $local_db_port,
                            'local_db_username' => $local_db_username,
                            'central_db_name' => $central_db_name,
                            'central_db_port' => $central_db_port,
                            'central_db_username' => $central_db_username
                        );

                        $data = json_encode($a);
                        $output = $curl->createGID2($data);

                        $createdGID = array();
                        $list = array();
                        $createdGID = $output['createdGID'];
                        $list = $output['list'];
                        $existing = $output['existingTerm'];
                        $checked = $checked_all;

                        //echo "<br>standardized ";
                        //print_r($standardized);
                        //echo "<br>checked ";
                        //print_r($checked_all);

                        $rows = $list;
                    }
                } elseif (isset($_POST['createNew'])) {

                    $cross = strip_tags($_POST['createNew']);
                    $term = strip_tags($_POST['term']);
                    $chosenID = strip_tags($_POST['chosenID']);
                    $fid = strip_tags($_POST['fid']);
                    $mid = strip_tags($_POST['mid']);
                    $gpid1_nval = strip_tags($_POST['gpid1_nval']);
                    $gpid2_nval = strip_tags($_POST['gpid2_nval']);
                    $locationID = strip_tags($_POST['locationID']);
                    $theParent = strip_tags($_POST['theParent']);
                    $cdate = strip_tags($_POST['cdate']);

                    $list = unserialize(base64_decode($_POST['list']));
                    $createdGID = unserialize(base64_decode($_POST['createdGID']));
                    $existing = unserialize(base64_decode($_POST['existing']));
                    $checked = unserialize(base64_decode($_POST['checked']));

                    $a = array(
                        'cdate' => $cdate,
                        'cross' => $cross,
                        'chosenID' => $chosenID,
                        'term' => $term,
                        'theParent' => $theParent,
                        'fid' => $fid,
                        'mid' => $mid,
                        'gpid1_nval' => $gpid1_nval,
                        'gpid2_nval' => $gpid2_nval,
                        'list' => $list,
                        'existingTerm' => $existing,
                        'createdGID' => $createdGID,
                        'locationID' => $locationID,
                        'userID' => Yii::app()->user->id
                    );

                    $output = $curl->createNew(json_encode($a));

                    $createdGID = array();
                    $list = array();
                    $createdGID = $output['createdGID'];
                    $list = $output['list'];
                    $existing = $output['existingTerm'];
                    $rows = $list;
                } elseif (isset($_POST['choose'])) {

                    $term = strip_tags($_POST['term']);
                    $cross = strip_tags($_POST['cross']);
                    $pedigree = strip_tags($_POST['pedigree']);
                    $id = strip_tags($_POST['id']);
                    $choose = strip_tags($_POST['choose']);
                    $fid = strip_tags($_POST['fid']);
                    $mid = strip_tags($_POST['mid']);
                    $female = strip_tags($_POST['female']);
                    $male = strip_tags($_POST['male']);
                    $locationID = strip_tags($_POST['locationID']);
                    $theParent = strip_tags($_POST['theParent']);
                    $gid = strip_tags($_POST['gid']);
                    $gpid1 = strip_tags($_POST['gpid1']);
                    $gpid2 = strip_tags($_POST['gpid2']);
                    $cdate = strip_tags($_POST['cdate']);

                    $list = unserialize(base64_decode($_POST['list']));
                    $createdGID = unserialize(base64_decode($_POST['createdGID']));
                    $existing = unserialize(base64_decode($_POST['existing']));
                    $checked = unserialize(base64_decode($_POST['checked']));

                    $userID = Yii::app()->user->id;

                    $output = $file_toArray->updateGID_createdGID($term, $pedigree, $id, $choose, $fid, $mid, $female, $male, $createdGID, $existing, $list, $userID, $theParent);
                    $output['cdate'] = $cdate;

                    $local_db_name = Yii::app()->request->getParam('local_db_name');
                    $local_db_port = Yii::app()->request->getParam('local_db_port');
                    $local_db_username = Yii::app()->request->getParam('local_db_username');
                    $central_db_name = Yii::app()->request->getParam('central_db_name');
                    $central_db_port = Yii::app()->request->getParam('central_db_port');
                    $central_db_username = Yii::app()->request->getParam('central_db_username');
                    $output['local_db_name'] = $local_db_name;
                    $output['local_db_port'] = $local_db_port;
                    $output['local_db_username'] = $local_db_username;
                    $output['central_db_name'] = $central_db_name;
                    $output['central_db_port'] = $central_db_port;
                    $output['central_db_username'] = $central_db_username;
                    /*     echo "choose: ".$cross;
                      echo ": ".$term."<br>";

                      echo "r: ".$r;
                     * */
                    $r = strcmp($term, $cross);
                    if ($r === 0) {
                        echo "<br>choose gid for cross";
                        $output["female"] = $female;
                        $output["male"] = $male;
                        $output["female_id"] = $fid;
                        $output["male_id"] = $mid;
                        $output["gpid1"] = $gpid1;
                        $output["gpid2"] = $gpid2;
                        $output["gid"] = $gid;
                        $output["locationID"] = $locationID;


                        $output = $curl->chooseGID_cross(json_encode($output));
                    } else {
                        $output = $curl->chooseGID(json_encode($output));
                    }

                    $createdGID = array();
                    $list = array();
                    $createdGID = $output['createdGID'];
                    $list = $output['list'];
                    $rows = $list;
                    $existing = $output['existingTerm'];
                } else {
                    //echo "<br><br><br> no curl";
                    $locationID = $_POST['location'];
                    $createdGID = unserialize(base64_decode($_POST['createdGID']));
                    $existing = unserialize(base64_decode($_POST['existing']));
                    $list = unserialize(base64_decode($_POST['list']));
                    $checked = unserialize(base64_decode($_POST['checked']));
                    $rows = $list;
                }
                if (count($rows)) {
                    foreach ($rows as $i => $row) : list($GID, $nval, $fid, $fremarks, $fgid, $female, $mid, $mremarks, $mgid, $male, $date) = $row;
                        $arr2[] = array('id' => $i + 1, 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks, 'date' => $date);

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
            } elseif (isset($_GET['yes'])) {//to process the remaining entries
                //echo "<br><br><br>yes page";
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
                //echo $values['path'] . "?" . $values['query'];
                //header("Location: " . $values['path'] . "?" . $values['query'] . "");
                ?>
                <html>
                    <body onload="storeLocal_process();">
                        <form action="<?php echo $values['path'] . "?" . $values['query']; ?>" method="post" id='process-id'>
                            <input type="hidden" name="process" value="true">
                            <input type="hidden" id ="location" name="location" value="">
                            <input type="hidden" id="list" name="list" value="">
                            <input type="hidden" id="createdGID" name="createdGID" value="">
                            <input type="hidden" id="checked" name="checked" value="">
                            <input type="hidden" id="existing" name="existing" value="">
                        </form>  
                    </body>
                </html>
                <?php
            } else {    // when page is refreshed
               // echo "page refreshed" . "<br>";
                // print_r($existing);
                ?>
                <html>
                    <body onload="storeLocal_refresh();">
                        <form action="" method="post" id='refresh-id'>
                            <input type="hidden" name="refresh" value="true">
                            <input type="hidden" id ="location-refresh" name="location" value="">
                            <input type="hidden" id="list-refresh" name="list" value="">
                            <input type="hidden" id="createdGID-refresh" name="createdGID" value="">
                            <input type="hidden" id="checked-refresh" name="checked" value="">
                            <input type="hidden" id="existing-refresh" name="existing" value="">
                        </form>  
                    </body>
                </html>
                <?php
            }
        } else {
            $this->render('login', array('model' => $model2));
        }
    }

    public function actionChooseGID() {
        Yii::app()->session['username'] = Yii::app()->user->id;
        $this->browserSession = Yii::app()->session['username'];
        $model2 = new LoginForm;

        if (isset($this->browserSession)) {
            $this->render('chooseGID');
        } else {
            $this->render('login', array('model' => $model2));
        }
    }

    public function actionContactUs() {
        $this->render('contactUs');
    }

    /* public function actionDiagram() {
      Yii::app()->session['username'] = Yii::app()->user->id;
      $this->browserSession = Yii::app()->session['username'];
      $model2 = new LoginForm;

      if (isset($this->browserSession)) {
      Yii::import('application.modules.curl');

      $curl = new curl();
      $arr = $curl->showDiagram();
      //print_r($arr);

      $tree = $arr['tree'];
      //$rows = $tree;

      $out = json_decode($tree);
      //echo "<br/>rows:<br/>";
      //print_r($rows);
      //echo "<br/>";
      $Data = $tree;
      $File = dirname(__FILE__) . '/../../json_files/diagram.json';
      //file_put_contents($File, $tree);
      $Handle = fopen($File, 'w');

      fwrite($Handle, $tree);
      print "Data Written";
      fclose($Handle);
      $this->render('diagram');
      } else {
      $this->render('login', array('model' => $model2));
      }
      } */

    public function actionBackend() {
        $dbFormModel = new databaseForm;
        $centralDBForm = new centralDBForm;

        $this->render('backend', array(
            'dbFormModel' => $dbFormModel,
            'centralDBForm' => $centralDBForm
        ));
    }
       public function actionSettings_browser() {
        $dbFormModel = new databaseForm;
        $centralDBForm = new centralDBForm;

        $this->render('settings_browser', array(
            'dbFormModel' => $dbFormModel,
            'centralDBForm' => $centralDBForm
        ));
    }

}

