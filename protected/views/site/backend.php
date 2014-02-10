<meta name="viewport" content="width=device-width, initial-scale=1.0">
<html>
    <body>
        <?php
        /*
         * Displays a data-browser that requires input for database details
         * Febuary 7, 2014
         * developer: Joanie C. Antonio <j.antonio@irri.org>
         */

        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'type' => 'horizontal',
            'id' => 'db_details',
            'htmlOptions' => array(
                'class' => 'well',
                'enctype' => 'multipart/form-data'
            ),
            'action' => array('site/login') 
        ));
        ?>
        <div id="backend_container" class="row">
            <div class="span6">
                <div class="span5">
                    <fieldset>
                        <legend>Local Database</legend>
                        <?php
                        $dbFormModel->database_name = 'local';
                        echo $form->textFieldRow($dbFormModel, 'database_name');
                        $dbFormModel->port_name = '3306';
                        echo $form->textFieldRow($dbFormModel, 'port_name');
                        $dbFormModel->database_username = Yii::app()->user->name;
                        echo $form->textFieldRow($dbFormModel, 'database_username');
                        $dbFormModel->database_password = '';
                        echo $form->passwordFieldRow($dbFormModel, 'database_password');
                        ?>
                    </fieldset>
                    <fieldset>
                        <legend>Central Database</legend>
                        <?php
                        $centralDBForm->database_name = 'central';
                        echo $form->textFieldRow($centralDBForm, 'database_name');
                        $centralDBForm->port_name = '3306';
                        echo $form->textFieldRow($centralDBForm, 'port_name');
                        $centralDBForm->database_username = Yii::app()->user->name;
                        echo $form->textFieldRow($centralDBForm, 'database_username');
                        $dbFormModel->database_password = '';
                        echo $form->passwordFieldRow($dbFormModel, 'database_password');
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
</html>
<script type='text/javascript'>
    $(document).ready(function(){
          $('#submit_btn').bind('click',function(){
               if ('localStorage' in window && window['localStorage'] != null) {
            try {
               
                var local_db_name = '<?php echo $local_database['db_name'] ?>';
                var local_db_port = '<?php echo $local_database['db_port']  ?>';
                var local_db_username = '<?php echo $local_database['db_username'] ?>';
                
                var central_db_name = '<?php echo $central_database['db_name'] ?>';
                var central_db_port = '<?php echo $central_database['db_port'] ?>';
                var central_db_username = '<?php echo $central_database['db_username'] ?>';
                
                localStorage.setItem('local_database_name', local_db_name);
                localStorage.setItem('local_database_port', local_db_port);
                localStorage.setItem('local_database_username', local_db_username);
                localStorage.setItem('central_database_name', central_db_name);
                localStorage.setItem('central_database_port', central_db_port);
                localStorage.setItem('central_database_username', central_db_username);
                
            } catch (e) {
                if (e === QUOTA_EXCEEDED_ERR) {
                    alert('Quota exceeded!');
                }
            }
        } else {
            alert('Cannot store user preferences as your browser do not support local storage');
        }
          });
    });
       window.addEventListener('storage', storageEventHandler, false);
    function storageEventHandler(event) {
        storeLocal();
    }
    function storeLocal1() {
        if ('localStorage' in window && window['localStorage'] != null) {
            try {
                document.getElementById('list1').value = localStorage.list;
                document.getElementById('location1').value = localStorage.locationID;

            } catch (e) {
                if (e === QUOTA_EXCEEDED_ERR) {
                    alert('Quota exceeded!');
                }
            }
        } else {
            alert('Cannot store user preferences as your browser do not support local storage');
        }
    }
</script>    