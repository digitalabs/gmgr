<body onload='storeLocal()'>  
  <?php
    /*
     * Displays a data-browser that requires input for database details
     * Febuary 7, 2014
     * developer: Joanie C. Antonio <j.antonio@irri.org>
     */
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php
        if (Yii::app()->user->id != NULL) { //logged in
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'type' => 'horizontal',
                'id' => 'db_details',
                'htmlOptions' => array(
                    'class' => 'well',
                    'enctype' => 'multipart/form-data'
                ),
                'action' => array('site/importer_file')
            ));
        } else { //not logged in
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'type' => 'horizontal',
                'id' => 'db_details',
                'htmlOptions' => array(
                    'class' => 'well',
                    'enctype' => 'multipart/form-data'
                ),
                'action' => array('site/login')
            ));
        }
        ?>
        <div id="backend_container" class="row">

            <div class="span6">
                <br/>
                <div>
                    <?php
                    echo CHtml::link('Back to main', array('site/importer'));
                    ?>
                </div>
                <div class="span5">
                    <fieldset>
                        <legend>Local Database</legend>
                        <?php
                        $dbFormModel->host = '';
                        echo $form->textFieldRow($dbFormModel, 'host');
                        $dbFormModel->database_name = '';
                        echo $form->textFieldRow($dbFormModel, 'database_name');
                        $dbFormModel->port_name = '';
                        echo $form->textFieldRow($dbFormModel, 'port_name');
                        $dbFormModel->database_username = '';
                        echo $form->textFieldRow($dbFormModel, 'database_username');
                        $dbFormModel->database_password = '';
                        echo $form->passwordFieldRow($dbFormModel, 'database_password');
                        ?>
                    </fieldset>
                    <fieldset>
                        <legend>Central Database</legend>
                        <?php
                        $centralDBForm->host = '';
                        echo $form->textFieldRow($centralDBForm, 'host');
                        $centralDBForm->database_name = '';
                        echo $form->textFieldRow($centralDBForm, 'database_name');
                        $centralDBForm->port_name = '';
                        echo $form->textFieldRow($centralDBForm, 'port_name');
                        $centralDBForm->database_username = '';
                        echo $form->textFieldRow($centralDBForm, 'database_username');
                        $centralDBForm->database_password = '';
                        echo $form->passwordFieldRow($centralDBForm, 'database_password');
                        ?>
                    </fieldset>
                    <div>
                        <?php
                        $this->widget('bootstrap.widgets.TbButton', array(
                            'type' => 'primary',
                            'id' => 'submit_btn',
                            'buttonType' => 'submit',
                            'label' => 'Submit',
                        ));
                        ?>
                    </div>
                </div> 
            </div>  
        </div>  
        <?php $this->endWidget(); ?>    
    </body>

<script type='text/javascript'>
    function storeLocal() {
        
            if ('localStorage' in window && window['localStorage'] != null) {
                try {
                    document.getElementById('databaseForm_host').value = localStorage.local_database_host;
                    document.getElementById('databaseForm_database_name').value = localStorage.local_database_name;
                    document.getElementById('databaseForm_port_name').value = localStorage.local_database_port;
                    document.getElementById('databaseForm_database_username').value = localStorage.local_database_username;
                    document.getElementById('databaseForm_database_password').value = localStorage.local_database_password;
                    document.getElementById('centralDBForm_host').value = localStorage.central_database_host;
                    document.getElementById('centralDBForm_database_name').value = localStorage.central_database_name;
                    document.getElementById('centralDBForm_port_name').value = localStorage.central_database_port;
                    document.getElementById('centralDBForm_database_username').value = localStorage.central_database_username;
                    document.getElementById('centralDBForm_database_password').value = localStorage.central_database_password;
                } catch (e) {
                    if (e === QUOTA_EXCEEDED_ERR) {
                        alert('Quota exceeded!');
                    }
                }
            } else {
                alert('Cannot store user preferences as your browser do not support local storage');
            }
      
    }

    window.addEventListener('storage', storageEventHandler, false);
    function storageEventHandler(event) {
        storeLocal();
    }
</script>    