<body onload='storeLocal()'>

    <div class="container">


<!--<p>Please fill out the following form with your login credentials:</p>-->

        <div class="form">
            <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'login-form',
                'type' => 'horizontal',
                'htmlOptions' => array('class' => 'well'),
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));
            ?>
            <h1>Please Sign In</h1>
            <p class="note">Fields with <span class="required">*</span> are required.</p>
            <?php
            echo CHtml::link('Check database settings', array('site/backend'), array('id' => 'database_link'));
            ?>   
            <div class="row">
                <?php $model->username = 'GUEST'; ?>
                <?php echo $form->labelEx($model, 'username'); ?>
                <?php echo $form->textField($model, 'username'); ?>
                <?php echo $form->error($model, 'username'); ?>

            </div>

            <div class="row">

                <?php $model->password = 'GUEST'; ?>
                <?php echo $form->labelEx($model, 'password'); ?>
                <?php echo $form->passwordField($model, 'password'); ?>
                <?php echo $form->error($model, 'password'); ?>


            </div>

            <div class="row buttons">
                <?php
                $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => 'Login'));
                ?>
            </div>

            <?php $this->endWidget(); ?>
        </div><!-- form -->
    </div>
</body>
<script type='text/javascript'>
    function storeLocal() {
        if (<?php echo isset($database_details); ?>) {
      
            if ('localStorage' in window && window['localStorage'] != null) {
                try {
                    var local_db_host = '<?php echo $database_details['local_db_host'] ?>';
                    var local_db_name = '<?php echo $database_details['local_db_name'] ?>';
                    var local_db_port = '<?php echo $database_details['local_db_port'] ?>';
                    var local_db_username = '<?php echo $database_details['local_db_username'] ?>';
                    var local_db_password = '<?php echo $database_details['local_db_password'] ?>';

                    var central_db_host = '<?php echo $database_details['central_db_host'] ?>';
                    var central_db_name = '<?php echo $database_details['central_db_name'] ?>';
                    var central_db_port = '<?php echo $database_details['central_db_port'] ?>';
                    var central_db_username = '<?php echo $database_details['central_db_username'] ?>';
                    var central_db_password = '<?php echo $database_details['central_db_password'] ?>';

                    localStorage.setItem('local_database_host', local_db_host);
                    localStorage.setItem('local_database_name', local_db_name);
                    localStorage.setItem('local_database_port', local_db_port);
                    localStorage.setItem('local_database_username', local_db_username);
                    localStorage.setItem('local_database_password', local_db_password);
                    localStorage.setItem('central_database_host', central_db_host);
                    localStorage.setItem('central_database_name', central_db_name);
                    localStorage.setItem('central_database_port', central_db_port);
                    localStorage.setItem('central_database_username', central_db_username);
                    localStorage.setItem('central_database_password', central_db_password);

                } catch (e) {
                    if (e === QUOTA_EXCEEDED_ERR) {
                        alert('Quota exceeded!');
                    }
                }
            } else {
                alert('Cannot store user preferences as your browser do not support local storage');
            }
        } else {
        }
    }

    window.addEventListener('storage', storageEventHandler, false);
    function storageEventHandler(event) {
        storeLocal();
    }

</script>    