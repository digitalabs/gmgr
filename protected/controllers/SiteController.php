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

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
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
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
            //$this->redirect(array('/site/contact'));
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
        $this->render('editor');
    }

    public function actionImporter() {
        //$dir = Yii::getPathOfAlias('application.mo');
        $uploaded = false;
        $model = new ImporterForm;


        //Collect user input form
        if (isset($_POST['ImporterForm'])) {
            $model->attributes = $_POST['ImporterForm'];
            $file = CUploadedFile::getInstance($model, 'file');

            //Validates input
            $exists = file_exists($file);
            if ($exists) {
                unlink($file);
            }

            if ($model->validate()) {
                //$uploaded = $file->saveAs($dir.'/'.$file->getName());
                //echo $uploaded;
                   Yii::app()->user->setFlash('success','Save data successfully');
                //import json class
                Yii::import('application.modules.json');
                //json file of the locationID

                $json = new json($_POST['location']);
                $json->location();
                $json->getFile();

                //import curl class
                Yii::import('application.modules.curl');
                //call curl: function parse
                $curl = new curl();
                $curl->parse();

                //import file_toArray class
                Yii::import('application.modules.file_toArray');
                // array from file output.csv
                $file_toArray = new file_toArray();
                $rows = $file_toArray->csv_output();


                //call php file
                //$this->redirect(array('site/importFileDisplay','location'=>$_POST['location']));
                $this->actionImportFileDisplay($rows);
            }
        } else {
            $this->render('importer', array(
                'model' => $model,
                'uploaded' => $uploaded,
                    // 'dir'=> $dir,
            ));
        }
    }

    public function actionImportFileDisplay($id = array()) {

        $arr = array();
        foreach ($id as $row) :
            list($GID, $nval, $female, $fid, $fremarks, $fgid, $male, $mid, $mremarks, $mgid) = $row;

            //$arr[] = array('id'=>1,'nval'=>$nval,'gid'=>$GID,'female'=>$female,'male'=>$male,'mgid'=>$mgid,'fremarks'=>$fremarks);
            $arr[] = array('id' => 1, 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'mgid' => $mgid, 'fgid' => $fgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks);
        endforeach;

        $arrayDataProvider = new CArrayDataProvider($arr, array(
            'id' => 'nval',
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        $params = array(
            'arrayDataProvider' => $arrayDataProvider,
        );
        if (!isset($_GET['ajax']))
            $this->render('importFileDisplay', $params);
        else
            $this->renderPartial('importFileDisplay', $params);
    }

    public function actionStandardTable() {

        //$filtersForm = new FilterPedigreeForm;
       
        /*
        if (isset($_POST['selectedIds'])) {
            $selected = $_POST['selectedIds']; //checkedbox

            for ($i = 0; $i < count($selected); $i++) {
                //echo "checked:".$checked[$i];
                $data = explode(',', $selected[$i]);

                //trim the entries
                $fid[$i] = strtok($data[0], '["');
                $mid[$i] = strtok($data[1], '["');

                $checked[$i] = $fid[$i];
            } 
     
			//json file of checked boxes
			$json = new json($_POST['checked']);
			$json->checkedBox();

			//call curl: function createdGID
			$curl = new curl();
			$curl->createdGID();

			// update createdGID.csv
			$file_toArray = new file_toArray();
			$rows = $file_toArray->update_csv_createdGID($fid, $mid, $checked);
		
	  }
		if (isset($_POST['standardize'])) {
			echo "standardize";
			//call curl: function standardization
			$curl = new curl();
			$curl->standardize();
		}
   
        //render
        $this->render('standardTable');*/
    }

    public function actionEditGermplasm() {

        $model = new editGermplasmForm;

        if (isset($_POST['editGermplasmForm'])) {
            $model->attributes = $_POST['editGermplasmForm'];
            if ($model->validate()) {
                $newGermplasmName = $_POST["editGermplasmForm"]['newGermplasmName'];
                $this->actionSaveGermplasm($newGermplasmName);
                //  Yii::app()->user->setFlash('editGermplasmForm','Submitted');
            }
        }
        $this->render('editGermplasm', array('model' => $model));
    }

    public function actionSaveGermplasm($name) {
        //$newGermplasmName = Yii::app()->getRequest()->getQuery('id');
        //echo "Saved:".$name;
        $this->renderPartial('savegermplasm');
    }

    public function actionCreatedGID() {
    
    /*
    $exists = file_exists(dirname(__FILE__).'/../modules/createdGID.csv');
	if ($exists) {
		unlink(dirname(__FILE__)."/../modules/createdGID.csv");
	}
	$exists = file_exists(dirname(__FILE__)."/../modules/sample.csv");
	if ($exists) {
		unlink(dirname(__FILE__)."/../modules/sample.csv");
    }
    $exists = file_exists("output.csv");
    if ($exists) {
        unlink(dirname(__FILE__)."/../modules/output.csv");
    }
    $exists = file_exists(dirname(__FILE__)."/../modules/newString.csv");
    if ($exists) {
        unlink(dirname(__FILE__)."/../modules/newString.csv");
    }
    $exists = file_exists(dirname(__FILE__)."/../modules/corrected.csv");
    if ($exists) {
        unlink(dirname(__FILE__)."/../modules/corrected.csv");
    }
    $exists = file_exists(dirname(__FILE__)."/../modules/checked.json");
    if ($exists) {
        unlink(dirname(__FILE__)."/../modules/checked.json");
    }
    $exists = file_exists(dirname(__FILE__)."/../modules/docinfo.json");
    if ($exists) {
        unlink(dirname(__FILE__)."/../modules/docinfo.json");
    }
    $exists = file_exists(dirname(__FILE__)."/../modules/existingTerm.csv");
    if ($exists) {
        unlink(dirname(__FILE__)."/../modules/existingTerm.csv");
    }

    $exists = file_exists(dirname(__FILE__)."/../modules/checked.csv");
    if ($exists) {
        unlink(dirname(__FILE__)."/../modules/checked.csv");
    }
    */ 
        Yii::import('application.modules.configDB');


        $fid = array();
        $mid = array();
        $checked = array();

       
        if (isset($_POST['selectedIds'])) {
            $selected = $_POST['selectedIds']; //checkedbox

            for ($i = 0; $i < count($selected); $i++) {
                //echo "checked:".$checked[$i];
                $data = explode(',', $selected[$i]);

                //trim the entries
                $fid[$i] = strtok($data[0], '["');
                $mid[$i] = strtok($data[1], '["');

                $checked[$i] = $fid[$i];
            }    
            Yii::import('application.modules.file_toArray');
            $file_toArray = new file_toArray();
            $standardized = $file_toArray->checkIf_standardize($fid);

            //json file of checked boxes
            Yii::import('application.modules.json');
            $json = new json($standardized);
            $json->checkedBox();

            //call curl: function createdGID
            Yii::import('application.modules.curl');
            $curl = new curl();
            $curl->createGID();

            // update createdGID.csv
            /*Yii::import('application.modules.file_toArray');
              $file_toArray = new file_toArray();
             $file_toArray->update_csv_correctedGID($fid, $mid, $checked);
            */
        }
        if (isset($_POST['standardize'])) {
        //call curl: function standardization
        $curl = new curl();
        $curl->standardize();
        }

        Yii::import('application.modules.file_toArray');
        $file_toArray = new file_toArray();
        $checked = $file_toArray->json_checked();
        
        // final is the array containing arrays of the pedigree lines (from the checkedboxes)
        Yii::import('application.modules.file_toArray');
        $final = $file_toArray->getPedigreeLine();
       // print_r($final[0]);
       //  echo "before:".count($final);
        // If we have an array with items
        /*if (count($final)) {
            // Create the pagination object
            Yii::import('application.modules.pagination');
            $pagination = new pagination($final, (isset($_GET['pagea']) ? $_GET['pagea'] : 1), 1);
            // Decide if the first and last links should show
            $pagination->setShowFirstAndLast(false);
            /* You can overwrite the default seperator $pagination->setMainSeperator(' | '); 
             *  Parse through the pagination class*/
        //    $pages = $pagination->getResults();
            
            // If we have items 
          /*  if (count($pages) != 0) {
                // Create the page numbers Loop through all the items in the array*/
               /* $count = 0;

                $checked = $file_toArray->csv_checked2();

                $arr=array(); $id_arr = array();$fem=  array();$mal=  array();
                $final_arr=array();
                for ($i = 0; $i < count($final); $i++) {
                    foreach ($final[$i] as $r) : list($id,$nval,$term,$GID, $methodID, $method, $locID, $location) = $r;
              
                    // echo "<br>".$id;
                     $id_arr[$i] = $id;
                    if(strpos($id,'/')){
                      $data1 = explode("/",$id);
                      $fem[$i] = $data1[0];
                      $mal[$i] = $data1[1];
                  //  echo $fem."and".$mal;
                   }
                   
                    //array_push($arr,array('id'=>$id,'nval'=>$nval,'term'=>$term,'GID'=>$GID,'methodID'=>$methodID,'method'=>$method,'locID'=>$locID,'location'=>$location));
                   array_push($arr,array('id'=>$id));
                    endforeach;
                }
           
              
                 
                  //  }
            //   }
         
            } 
           
       
        }*/
          //print_r($arr);
            /*DataProvider for the upper table, Created GID*/ 
         /*  $dataProvider = new CArrayDataProvider($arr, array(                 
                    'keyField'=> 'id',
                    'pagination' => array(
                         'pageSize' => 1,
                    ),
                )
             );*/
            
            
            /*Open corrected.csv and process file*/
            $myfile = dirname(__FILE__).'/../modules/corrected.csv';
            
            $fp = fopen($myfile, 'r');
            $rows = array();
            while(($row = fgetcsv($fp)) !== FALSE){
                $rows[] = $row;
            }
            fclose($fp);
           
            
            /*If we have an array with items*/
            if(count($rows)){
                foreach ($rows as $i => $row) : list($GID, $nval, $fid, $fremarks, $fgid, $female, $mid, $mremarks, $mgid, $male) = $row;
                    $arr2[] = array('id' => $i+1, 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks);
                    
                endforeach;
            }
        
            /*DataProvider for the lower table, Germplasm List*/
            $GdataProvider = new CArrayDataProvider($arr2, array(
                  'keyField'=> 'id',
                    'pagination' => array(
                         'pageSize' => 5,
                    ), 
            ));
           
         //render page with ajax   
         if(Yii::app()->request->isAjaxRequest) $this->renderPartial('createdGID', array('GdataProvider'=>$GdataProvider),false,true);
         else $this->render('createdGID', array('GdataProvider'=>$GdataProvider));
          //Yii::app()->controller->renderPartial('createdGID', array('dataProvider' => $dataProvider,'GdataProvider'=>$GdataProvider));
        //$this->render('createdGID', array('dataProvider' => $dataProvider,'GdataProvider'=>$GdataProvider));
       
    }
 
/**
 * @return array flash message keys array
 */
   /* public function getFlashKeys()
    {
        $counters=$this->getState(self::FLASH_COUNTERS);
        if(!is_array($counters)) return array();
        return array_keys($counters);
    }*/
    public function actionShowGID(){
		
		
         $filtersForm = new FilterPedigreeForm;

         /**
          * Added by Joanie Antonio
          */
       if (isset($_POST['checked'])) {
        $checked = $_POST['checked'];
        $fid = $_POST['fid'];
        $mid = $_POST['mid'];
        
        //json file of checked boxes
        $json = new json($_POST['checked']);
        $json->checkedBox();

        //call curl: function createdGID
        $curl = new curl();
        $curl->createdGID();

    
       }
       
       if (isset($_POST['standardize'])) {
        //call curl: function standardization
         $curl = new curl();
         $curl->standardize();
       }
         /*End*/

        // $fid = $_POST['fid'];
        //$mid = $_POST['mid'];
        //import json class
        Yii::import('application.modules.json');

        //json file of checked boxes
        //$json = new json($_POST['checked']);
        // $json->checkedBox();
        //import curl class
        Yii::import('application.modules.curl');

        //call curl: function standardization
        $curl = new curl();
        $curl->standardize();


        //import file_toArray class
        Yii::import('application.modules.file_toArray');
        // array from file output.csv
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
            $arr[] = array('id' => CJSON::encode(array($fid, $mid)), 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks);

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
   public function actionOutput(){
	   
	   $filtersForm = new FilterPedigreeForm;
       
        //import curl class
          Yii::import('application.modules.curl');
          
		  //call curl: function standardization
		  $curl = new curl();
		  $curl->standardize();
				
       
	   Yii::import('application.modules.file_toArray');
        $file_toArray = new file_toArray();
        $rows = $file_toArray->csv_corrected();
        //print_r($rows);
        
		foreach ($rows as $i => $row) :
            //foreach ($rows as $row) :
            list($GID, $nval, $fid, $fremarks, $fgid, $female, $mid, $mremarks, $mgid, $male) = $row;

            CHtml::hiddenField('hiddenMid', $mid);
            CHtml::hiddenField('hiddenFid', $fid);
            /* For reference, pls do not delete
             * developer: J.Antonio */
            // $arr[] = array('id'=>CJSON::encode(array('nval'=>$nval,'gid'=>$GID,'female'=>$female,'male'=>$male,'fgid'=>$fgid,'mgid'=>$mgid,'fremarks'=>$fremarks,'mremarks'=>$mremarks)),'nval'=>$nval,'gid'=>$GID,'female'=>$female,'male'=>$male,'fgid'=>$fgid,'mgid'=>$mgid,'fremarks'=>$fremarks,'mremarks'=>$mremarks);
            $arr[] = array('id' => CJSON::encode(array($fid, $mid)), 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks);

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
        $this->render('output', array(
            'filtersForm' => $filtersForm,
            'dataProvider' => $dataProvider,
        ));
   }
   public function actionAssignGID()
   {
       $this->render('assignGID');
   }
}
