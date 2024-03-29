<body onload='storeLocal()'>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<html>

    <body>
        <?php
        $this->pageTitle = Yii::app()->name . ' - Importer';
        $this->breadcrumbs = array(
            'Importer',
        );
        ?>


        <span id="ajax-loading-indicator">
        </span>
        <?php
        /** @var BootActiveForm $form */
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'type' => 'horizontal',
            'id' => 'pedigreeImport',
            'method' => 'post',
            'enableAjaxValidation' => false,
            'htmlOptions' => array('class' => 'well', 'enctype' => 'multipart/form-data'),
            'action' => array('site/importFileDisplay')
        ));
        ?>

        <!--div to grey out the screen while loading indicator is on-->
        <div id='screen'>

        </div>

        <div id="div-container" class="row">

            <div class="span14">
                <div id='div_link'>
                    <p class='instruction'>

                    </p>			
                </div>
                <div class="row">

                    <div class="span1"></div>
                </div>  
                <div class="span6">

                    <fieldset>
                        <?php
                        echo CHtml::link('Check database settings', array('site/settings_browser'), array('id' => 'database_link'));
                        ?>
                        <legend>Upload File</legend>
                        <b>File Type:</b> Breeders Cross Histories &nbsp; &nbsp;
                        <a href="Nomenclature Rules/Nomenclature%20Rules.htm">View Nomenclature Rules</a> <br/><br/>
                        <br>
                        <?php
                        echo CHtml::activefileField($model, 'file');
                        echo CHtml::error($model, 'file');
                        ?>
                        <?php
                        echo CHtml::link('Download sample file', Yii::app()->baseUrl . '/csv_files/germplasmList.csv');
                        ?>
                        <br><br/>
                        <div id="select-location">
                            <span>Location: </span>

                            <?php
                            $myfile = dirname(__FILE__) . '/../../../csv_files/location.csv';


                            $fin = fopen($myfile, 'r');

                            echo '<select name="location"  class="ddlClass" >';

                            while ($line = fgetcsv($fin, 0, "#")) {
                                // if (count($line) != 3) {
                                //   print_r($line);

                                echo '<option name="location[]"  value="' . $line[0] . '">' . $line[2] . ': ' . $line[1] . '</option>';
                                //}
                            }
                            ?>
                        </div>
                        <?php
                        fclose($fin);
                        echo "</select></br>";
                        ?>

                        <?php
                        echo "</br>";
                        $this->widget('bootstrap.widgets.TbButton', array(
                            'buttonType' => 'submit',
                            'id' => 'uploadFile',
                            'type' => 'primary',
                            'label' => 'Upload list',
                            'htmlOptions' => array(
                                'onclick' => 'js:
                                        var dataUser = $(".ddlClass option:selected").val();
                                        $("#location").val(dataUser);
                                        
                                    ',
                            ),
                        ));
                        ?>

                    </fieldset><br>
                </div>
                <?php
                echo CHtml::textField('location', '', array(
                    'id' => 'location',
                    'form' => 'pedigreeImport',
                    'class' => 'hidden',
                ));
                echo CHtml::submitButton('Submit', array(
                    'id' => 'submit-btn',
                    'class' => 'hidden',
                    'form' => 'pedigreeImport'
                ));
                ?>
                <!--</div>-->
                </fieldset><br>
            </div>
        </div>
    </div>
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="info">
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
    <?php endif; ?>

    <?php $this->endWidget(); ?>
</body></html>
<script type="text/javascript">
    function storeLocal() {
        if (<?php echo isset($database_details); ?>) {

            if ('localStorage' in window && window['localStorage'] != null) {
                try {
                                 //******get database settings
                document.getElementById('local_db_host').value = localStorage.local_database_host;
                document.getElementById('local_db_name').value = localStorage.local_database_name;
                document.getElementById('local_db_port').value = localStorage.local_database_port;
                document.getElementById('local_db_username').value = localStorage.local_database_username;
                document.getElementById('local_db_password').value = localStorage.local_database_password;
                document.getElementById('central_db_host').value = localStorage.central_database_host;
                document.getElementById('central_db_name').value = localStorage.central_database_name;
                document.getElementById('central_db_port').value = localStorage.central_database_port;
                document.getElementById('central_db_username').value = localStorage.central_database_username;
                document.getElementById('central_db_password').value = localStorage.central_database_password;


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

    $(document).ready(function() {
        var pop = function() {
            $('#screen').css({opacity: 0.4, 'width': $(document).width(), 'height': $(document).height()});
            $('body').css({'overflow': 'hidden'});
            $('#ajax-loading-indicator').css({'display': 'block'});
        }
        $('#uploadFile').click(pop);

    });
    window.onbeforeload = function() {

    }
<?php
Yii::app()->clientScript->registerScript(
        'myHideEffect', '$(".info").animate({opacity: 1.0}, 3000).fadeOut("slow");', CClientScript::POS_READY
);
?>
</script>