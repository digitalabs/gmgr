<?php

Yii::import('application.components.Controller');

class LoginController extends Controller {

    public $browserSession = NULL;
    public $mode = NULL;
    public $tvp = NULL;
    public $activity = NULL;
    public $toProcess = false;

    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'users' => array('*'),
            ),
        );
    }

    protected function checkEntry() {
        $this->browserSession = BrowserSession::model()->findByAttributes(array('user_id' => Yii::app()->user->id));

        if (!empty($this->browserSession)) {
            if (!empty($this->browserSession->mode)) {
                $this->mode = $this->browserSession->mode;
                $this->tvp = $this->browserSession->tvp;
                $this->activity = $this->browserSession->activity;
            }
        }
    }

    public function actionEntryPoint() {
        if ($this->action->id == 'entryPoint')
            $this->checkEntry();

        if (isset($_POST['EntryPoint']) or !empty($this->mode)) {
            if (isset($_POST['EntryPoint'])) {
                if (isset($_POST['EntryPoint']['mode']))
                    $this->mode = $_POST['EntryPoint']['mode'];

                if (isset($_POST['EntryPoint']['tvp']))
                    $this->tvp = $_POST['EntryPoint']['tvp'];

                if (isset($_POST['EntryPoint']['activity']))
                    $this->activity = $_POST['EntryPoint']['activity'];
            }

            Yii::app()->session['entrypoint_isset'] = 1;
            Yii::app()->session['userMode'] = $this->mode;
            Yii::app()->session['tvp_isset'] = $this->tvp;
            Yii::app()->session['activity_isset'] = $this->activity;

            if ($this->saveEntry())
                $this->processEntry();
        }

        $this->render('entry_point');
    }

    public function actionIndex() {

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
                'central_db_host' => $central_db_host,
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
                'central_db_host' => $central_db_host,
                'central_db_name' => $central_db_name,
                'central_db_port' => $central_db_port,
                'central_db_username' => $central_db_username,
                'central_db_password' => $central_db_password
            );
            $this->render('login', array('model' => $model, 'database_details' => $database_details));
        }
    }

}

