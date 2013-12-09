
<?php
Yii::import('application.modules.configDB');
Yii::import('application.modules.file_toArray');
Yii::import('application.modules.curl');

 $form =$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type' => 'horizontal',
        'id' => 'assign-gid-form',
        'action' => array('/site/SaveGermplasm'),
    ));
 

?>
<fieldset>
    <legend>Edit Germplasm Name</legend>

    <?php $model->germplasmName = $_GET['germplasm']; ?>
    <?php echo $form->textFieldRow($model, 'germplasmName', array('hint' => $_GET['error'])); ?>


    <?php echo $form->textFieldRow($model, 'newGermplasmName') ?>

    <?php
    //CHtml::hiddenField('error', $_GET['error']); 
    
    ?>

    <?php
    echo CHtml::hiddenField('list', "");

    $this->widget('bootstrap.widgets.TbButton', array(
        'type' => 'primary',
        'label' => 'Save Changes',
        'htmlOptions' => array(
            'onclick' => 'js:
            if ("localStorage" in window && window["localStorage"] != null) {
            try {
                console.log(localStorage.list);
                $("#list").val(localStorage.list);
                $("#submit-btn").click();
            } catch (e) {
                if (e === QUOTA_EXCEEDED_ERR) {
                    alert("Quota exceeded!");
                }
            }
        } else {
            alert("Cannot store user preferences as your browser do not support local storage");
        }
                     
                ',
        ),
    ));
    
     echo CHtml::submitButton('Submit', array(
        'id' => 'submit-btn',
        'class' => 'hidden',
        'form' => 'assign-gid-form',
            //'onclick' => 'js: alert("hello");',
    ));
    ?>
    
</fieldset>	 
<?php $this->endWidget();

?>

