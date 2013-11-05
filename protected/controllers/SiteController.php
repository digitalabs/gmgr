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
        $this->render('editor');
    }

    public function actionImporter() {

        //$dir = Yii::getPathOfAlias('application.modules');
        // $uploaded = false;

        $model = new ImporterForm;
        $file = dirname(__FILE__) . '/../../csv_files/germplasmList.csv';


        //Collect user input form
        if (isset($_POST['ImporterForm'])) {
            // $model->attributes = $_POST['ImporterForm'];
            /// $file = CUploadedFile::getInstance($model, 'file');
            //Delete existing files  
            $exists = file_exists(dirname(__FILE__) . '/../../csv_files/createdGID.csv');
            if ($exists) {
                unlink(dirname(__FILE__) . '/../../csv_files/createdGID.csv');
            }
            $exists = file_exists(dirname(__FILE__) . "/../../csv_files/sample.csv");
            if ($exists) {
                unlink(dirname(__FILE__) . "/../../csv_files/sample.csv");
            }
            $exists = file_exists(dirname(__FILE__) . "/../../csv_files/output.csv");
            if ($exists) {
                unlink(dirname(__FILE__) . "/../../csv_files/output.csv");
            }
            $exists = file_exists(dirname(__FILE__) . "/../../csv_files/newString.csv");
            if ($exists) {
                unlink(dirname(__FILE__) . "/../../csv_files/newString.csv");
            }
            $exists = file_exists(dirname(__FILE__) . "/../../csv_files/corrected.csv");
            if ($exists) {
                unlink(dirname(__FILE__) . "/../../csv_files/corrected.csv");
            }
            $exists = file_exists(dirname(__FILE__) . "/../../json_files/checked.json");
            if ($exists) {
                unlink(dirname(__FILE__) . "/../../json_files/checked.json");
            }
            $exists = file_exists(dirname(__FILE__) . "/../../json_files/docinfo.json");
            if ($exists) {
                unlink(dirname(__FILE__) . "/../../json_files/docinfo.json");
            }
            $exists = file_exists(dirname(__FILE__) . "/../../csv_files/existingTerm.csv");
            if ($exists) {
                unlink(dirname(__FILE__) . "/../../csv_files/existingTerm.csv");
            }
            $exists = file_exists(dirname(__FILE__) . "/../../csv_files/checked.csv");
            if ($exists) {
                unlink(dirname(__FILE__) . "/../../csv_files/checked.csv");
            }
             $exists = file_exists(dirname(__FILE__) . "/../../json_files/location.json");
            if ($exists) {
                unlink(dirname(__FILE__) . "/../../json_files/location.json");
            }
			  $exists = file_exists(dirname(__FILE__) . "/../../json_files/tree.json");
            if ($exists) {
                unlink(dirname(__FILE__) . "/../../json_files/tree.json");
            }	 
			$exists = file_exists(dirname(__FILE__) . "/../../json_files/term.json");
            if ($exists) {
                unlink(dirname(__FILE__) . "/../../json_files/term.json");
            }

            if ($model->validate()) {
                echo "location:".$_POST['location'];
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
                $this->redirect(array('site/importFileDisplay'));
               //$this->actionImportFileDisplay();
            }
        } else {
            /* $this->render('importer', array(
              'model' => $model,
              'uploaded' => $uploaded,

              )); */
            $this->render('importer', array(
                'model' => $model,
            ));
        }
    }

    public function actionImportFileDisplay() {

        $arr = array();
		$filtersForm = new FilterPedigreeForm;
		
		//import file_toArray class
        Yii::import('application.modules.file_toArray');
        // array from file output.csv
        $file_toArray = new file_toArray();
        $id = $file_toArray->csv_output();
		
        foreach ($id as $row) :
            list($GID, $nval, $female, $fid, $fremarks, $fgid, $male, $mid, $mremarks, $mgid) = $row;

            //$arr[] = array('id'=>1,'nval'=>$nval,'gid'=>$GID,'female'=>$female,'male'=>$male,'mgid'=>$mgid,'fremarks'=>$fremarks);
            //$arr[] = array('id' => 1, 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'mgid' => $mgid, 'fgid' => $fgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks);
            $arr[] = array('id' => CJSON::encode(array($fid, $mid)), 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks);
        endforeach;

		if (isset($_GET['FilterPedigreeForm'])){
            $filtersForm->filters = $_GET['FilterPedigreeForm'];
		}	
        $filteredData = $filtersForm->filter($arr);
        $dataProvider = new CArrayDataProvider($filteredData, array(
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        /*$params = array(
            'arrayDataProvider' => $arrayDataProvider,
        );*/
        /*if (!isset($_GET['ajax']))
            $this->render('importFileDisplay', $params);
        else
            $this->renderPartial('importFileDisplay', $params);
		*/	
		if (!isset($_GET['ajax'])){
			$this->render('importFileDisplay', array(
				'filtersForm' => $filtersForm,
				'dataProvider' => $dataProvider,
			));
		}else
		{
		    $this->render('importFileDisplay', array(
				'filtersForm' => $filtersForm,
				'dataProvider' => $dataProvider,
			));
		}
    }

    public function actionEditGermplasm() {

        $model = new editGermplasmForm;

        if (isset($_POST['editGermplasmForm'])) {
            $model->attributes = $_POST['editGermplasmForm'];
            if ($model->validate()) {
                $newGermplasmName = $_POST["editGermplasmForm"]['newGermplasmName']; //Gets the Input 
                //echo $newGermplasmName;
                $this->actionSaveGermplasm($newGermplasmName);
                //  Yii::app()->user->setFlash('editGermplasmForm','Submitted');
            }
        }
        $this->render('editGermplasm', array('model' => $model));
    }

    public function actionSaveGermplasm($name) {

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
	
            //Open corrected.csv and process file
          $myfile = dirname(__FILE__).'/../../csv_files/corrected.csv';
          
		 $filtersForm = new FilterPedigreeForm;
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
			
            if (isset($_GET['FilterPedigreeForm']))
			
            $filtersForm->filters = $_GET['FilterPedigreeForm'];

        //get array data and create dataProvider
        $filteredData = $filtersForm->filter($arr2);
            /*DataProvider for the lower table, Germplasm List*/
          $GdataProvider = new CArrayDataProvider($filteredData, array(
                  'keyField'=> 'id',
                    'pagination' => array(
                         'pageSize' => 5,
                    ), 
            ));
     
         //render page with ajax   
         if(Yii::app()->request->isAjaxRequest) $this->renderPartial('createdGID', array( 'filtersForm' => $filtersForm,'GdataProvider'=>$GdataProvider),false,true);
         else $this->render('createdGID', array(  'filtersForm' => $filtersForm,'GdataProvider'=>$GdataProvider));
         
    }
       public function actionAssignGID(){
	   
	     $arrSelectedIds = array();
	     $filtersForm = new FilterPedigreeForm;
	    if(isset($_POST['Germplasm']['gid']) && ($_POST['Germplasm']['gid']!=''))
		{
			Yii::import('application.modules.file_toArray');
			Yii::import('application.modules.json');
			Yii::import('application.modules.curl');
			
			 if(!empty($_POST['Germplasm']['gid'])){
				$selected = $_POST['Germplasm']['gid'];
				//echo "selected:";
				//var_dump($selected);
					
					$idArr = explode(',',$selected);
					//var_dump($idArr);
			   foreach($idArr as $index => $id){
				  $id = strtr($id, array('["'=>'','"]'=>''));
				 //echo intval($id)."<br/>";
				 $arrSelectedIds[$index] = (int)($id);
			  }
		     }
			   //Deletes existing checked germplasm in case the page reloads and to avoid duplication of createdGID for the checked items.
			   	$exists = file_exists(dirname(__FILE__)."/../../json_files/checked.json");
				if ($exists) {
					unlink(dirname(__FILE__)."/../../json_files/checked.json");
				}
				$exists = file_exists(dirname(__FILE__)."/../../csv_files/checked.csv");
				if ($exists) {
					unlink(dirname(__FILE__)."/../../csv_files/checked.csv");
				}
				$exists = file_exists(dirname(__FILE__)."/../../csv_files/createdGID.csv");
				if ($exists) {
					unlink(dirname(__FILE__)."/../../csv_files/createdGID.csv");
				}
				
				 $file_toArray = new file_toArray();
				 $standardized = $file_toArray->checkIf_standardize($arrSelectedIds);

				//json file of checked boxes
				$json = new json($standardized);
				$json->checkedBox();

				//call curl: function createdGID
				$curl = new curl();
				$curl->createGID();
				
		   // echo "createdGID: <br>";
		    //print_r($file_toArray->csv_createdGID());
		   
		}
		
	      //Open corrected.csv and process file
          $myfile = dirname(__FILE__).'/../../csv_files/corrected.csv';
            
            $fp = fopen($myfile, 'r');
            $rows = array();
            while(($row = fgetcsv($fp)) !== FALSE){
                $rows[] = $row;
            }
            fclose($fp);
           
            
            //If we have an array with items
            if(count($rows)){
                foreach ($rows as $i => $row) : list($GID, $nval, $fid, $fremarks, $fgid, $female, $mid, $mremarks, $mgid, $male) = $row;
                    $arr2[] = array('id' => $i+1, 'nval' => $nval, 'gid' => $GID, 'female' => $female, 'male' => $male, 'fgid' => $fgid, 'mgid' => $mgid, 'fremarks' => $fremarks, 'mremarks' => $mremarks);
                    
                endforeach;
            }
           if(isset($_GET['FilterPedigreeForm']))
			
            $filtersForm->filters = $_GET['FilterPedigreeForm'];

        //get array data and create dataProvider
        $filteredData = $filtersForm->filter($arr2);
            //DataProvider for the lower table, Germplasm List
          $GdataProvider = new CArrayDataProvider($filteredData, array(
                  'keyField'=> 'id',
                   'pagination' => array(
                         'pageSize' => 5,
                    ), 
            ));
            
        //Render the page AssignGID.php   
	  	if ( Yii::app()->request->getIsAjaxRequest() && isset($_GET["ajax"])) {
				$this->render('assignGID', array('filtersForm' => $filtersForm,
				'selected' => $arrSelectedIds,'GdataProvider'=>$GdataProvider
			));
		}else{
		      //open and store checked boxes
			$myfile2 = dirname(__FILE__).'/../../json_files/checked.json';
            
            $fp2 = fopen($myfile2, 'r');
            $rows2 = array();
            while(($row2 = fgetcsv($fp2)) !== FALSE){
                $rows2[] = $row2;
            }
            fclose($fp2);
          // echo "rows:";
         
          $checked = $rows2;
            $this->render('assignGID', array('filtersForm' => $filtersForm,
				'selected' => $checked,'GdataProvider'=>$GdataProvider
			));
		}
   }
    

    public function actionOutput() {

        $filtersForm = new FilterPedigreeForm;

        //import curl class
        Yii::import('application.modules.curl');

        //call curl: function standardization
        $curl = new curl();
        $curl->standardize();


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
        $this->render('output', array(
            'filtersForm' => $filtersForm,
            'dataProvider' => $dataProvider,
        ));
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

    public function actionChooseGID() {
        $this->render('chooseGID');
    }

    public function actionContactUs() {
        $this->render('contactUs');
    }

}
