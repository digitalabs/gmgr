<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/parsley.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/parsley.min.js"></script>
<!--*******************modal*************-->
<div id='modal-window' class='modal hide fade in' style='display:none;'></div>
<?php
$modalForm = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'type' => 'horizontal',
    'id' => 'assign-gid-form',
     'enableAjaxValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
    'action' => array('/site/output'),
    'htmlOptions' => array('data-validate' => 'parsley'),
        ));
?>
<div class='modal-header'>
    <a class='close' data-dismiss='modal'>&times;</a>
    <h4>Edit Germplasm</h4>
</div>
<div class='modal-body'>
    <p>
        <?php $model->germplasmName = $_POST['germplasmName']; ?>
        <?php echo $modalForm->textFieldRow($model, 'germplasmName', array('readonly' => true, 'hint' => $_POST['error'])); ?>
        <?php
        echo CHtml::hiddenField('error', $_POST['error']);
        echo CHtml::hiddenField('germplasm_name_old', $model->germplasmName);
        echo CHtml::hiddenField('germplasmName', $model->germplasmName);
        ?>
        <?php echo $modalForm->textFieldRow($model, 'newGermplasmName', array('id' => 'germplasm_name_new', 'data-placement' => 'right', 'data-required' => 'true')); ?>
        <span id="notification" style='display:none;'> </span><br/>
        <span id="notification2" style='display:none;'> </span>
        <?php echo $modalForm->error($model, 'newGermplasmName') ?>

    </p>

</div>
<div class='modal-footer'>
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'type' => 'primary',
        'id' => 'save-changes-btn',
        'label' => 'Save changes',
    ));
    echo CHtml::hiddenField('_new', '');
    echo CHtml::hiddenField('refresh', '');
    echo CHtml::hiddenField('list', "");
    echo CHtml::hiddenField('location', "");
    echo CHtml::hiddenField('newGermplasmName', "");
    ?>

    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Close',
        'url' => '#',
        'type' => 'primary',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    ));
    /* echo CHtml::submitButton('Submit', array(
      'id' => 'submit-btn',
      'class' => 'hidden',
      'form' => 'assign-gid-form'
      )); */
    ?>
    <?php $this->endWidget();
    ?>   
</div>
<div id="sample" style="display:none;">

</div>
<!--****************end for modal********************-->

<script>
    $(document).ready(function() {
        //$( '#assign-gid-form' ).parsley(); 

        $('#save-changes-btn').click(function() {
           // alert($("#germplasm_name_new").parsley("validate"));
            if ("localStorage" in window && window["localStorage"] != null) {
                try {
                    //console.log(localStorage.list);
                    $("#list").val(localStorage.list);
                } catch (e) {
                    if (e === QUOTA_EXCEEDED_ERR) {
                        alert("Quota exceeded!");
                    }
                }
                var oldGermplasmName = $("#germplasm_name_old").val();
                var newGermplasmName1 = $("#germplasm_name_new").val();
                var error = $("#error").val();
                var list = $("#list").val();
                $("#newGermplasmName").val(newGermplasmName);


                if (newGermplasmName1 === "") {

                    $("#notification").html("This is a required field.").addClass("flash-error").fadeIn();

                    return false;
                }
                else
                {
                    $("#refresh").val(1);
                    $("#_new").val(newGermplasmName1);

                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '<?php echo Yii::app()->createUrl('site/savegermplasm'); ?>',
                        data: {list: list, germplasmName: oldGermplasmName, newGermplasmName: newGermplasmName1, error: error},
                        success: function(data) {
                            $("#sample").html(data);
                            var updated = $("#update").val();
                            var list1 = $("#list1").val();
                            var error1 = $("#error1").val();
                            var gid1 = $("#gid1").val();

                            if (updated == 1) {
                                if ("localStorage" in window && window["localStorage"] != null) {
                                    try {
                                        localStorage.removeItem("update");
                                        localStorage.removeItem("list");

                                        console.log(list1);
                                        localStorage.setItem("list", list1);
                                        localStorage.setItem("update", updated);

                                        document.getElementById("list").value = localStorage.list;
                                        document.getElementById("location").value = localStorage.locationID;
                                        $("#submit-btn").click();

                                    } catch (e) {
                                        if (e === QUOTA_EXCEEDED_ERR) {
                                            alert("Quota exceeded!");
                                        }
                                    }
                                } else {
                                    alert("Cannot store user preferences as your browser do not support local storage");
                                }
                            } else {
                                $("#germplasm_name_new").focus();
                                $("#notification").text("ERROR.Germplasm name is not in standardized format. Please edit the germplasm name. Hint is next to germplasm name text box").css("color", "red");
                                $("#notification2").text(error1 + gid1).css("color", "red");
                                return false;
                            }
                        }
                    });
                }
            } else {
                alert("Cannot store user preferences as your browser do not support local storage");
            }
        });

    });
</script>