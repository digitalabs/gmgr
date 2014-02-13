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
                    <div class="span4">
                        <fieldset>

                            <br/>
                            
                            <input type="radio" name="group1" value="BreedersCrossHistories" checked> Breeders Cross Histories &nbsp; &nbsp;
                            <a href="Nomenclature Rules/NomenclatureRules.htm">Nomenclature Rules</a> <br/><br/>
                             <input type="radio" name="group2" value="singleHit" checked> Accept single hit search<br><br/>
                            <?php
                            echo CHtml::link('Check database settings', array('site/settings_browser'), array('id' => 'database_link'));
                            ?>

                            <br>
                            <br>
                            <br>
                        </fieldset><!--<br>
                        <fieldset>
                            <legend></legend>
                            <input type="radio" name="group2" value="singleHit" checked> Accept single hit search<br>
                            <br>

                        </fieldset><br>

                    </div>
                    <div class="span1"></div>-->
                    </div>  
                    <div class="span6">

                        <fieldset>
                            <legend>Upload File</legend>
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
