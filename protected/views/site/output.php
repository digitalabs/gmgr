<!--div to grey out the screen while loading indicator is on-->
<body onload="storeLocal()">

    <div id='screen'>
    </div>
    <span id="ajax-loading-indicator">
    </span>
    <!---End for loading indicators-->

    <!--****For edit germplasm modal dialog-->

    <div id="editGermplasmNameModal" class="modal hide fade in" style="display: none;"></div>

    <?php
    $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type' => 'horizontal',
        'id' => 'assign-gid-form',
        'enableAjaxValidation' => true,
        'action' => array('/site/assignGID'),
        'htmlOptions' => array('class' => 'well well-small'),
    ));

    if (isset($dataProvider2)) {
        ?>
        <div id="non_standardized_table">
            <h4 style = " border-bottom: 0px solid #999;text-align: left;">Table 1. List of germplasm <b>not</b> in <b>standardized</b> format.</h4>
            <?php
            $this->widget('ext.selgridview.BootSelGridView', array(
                'id' => 'pedigreeGrid2',
                'dataProvider' => $dataProvider2,
                'beforeAjaxUpdate' => 'js:
					function (id, options) {
						options.data = {
							list: $("#list").val(),
							locationID: $("#location").val(),
							location: $("#location").val(),
							next:1
						};
						options.type = "post";
					}
				',
                'filter' => $filtersForm2,
                'selectableRows' => 2,
                'enablePagination' => true,
                'columns' => array(
                    array(
                        'header' => 'Cross Name',
                        'value' => 'CHtml::encode($data["nval"])',
                        'name' => '',
                        'filter' => CHtml::textField('FilterPedigreeForm2[nval]', isset($_GET['FilterPedigreeForm2']['nval]']) ? $_GET['FilterPedigreeForm2']['nval'] : ''),
                        'htmlOptions' => array(
                            'style' => 'width:50px;',
                        )
                    ),
                    array(
                        'header' => 'Female Parent',
                        'name' => 'female',
                        'type' => 'raw',
                        'value' => function ($data) {

                            if (strcmp(CHtml::encode($data["fremarks"]), "in standardized format") == 0) {
                                $your_array = array();
                                $your_array = explode("#", CHtml::encode($data["fgid"]));
                                $your_array = implode("<br>", $your_array);
                                $fgid = $your_array;
                                return "<b>" . CHtml::encode($data["female"]) . "</b>" . "" . $fgid . "";
                            } else {

                                echo "<input type='hidden' class='" . $data["female"] . "' name='female' value='" . $data["female"] . "'>";
                                echo "<input type='hidden' class='" . $data["female"] . "' name='fremarks' value='" . $data["fremarks"] . "'>";
                                return "<div class='j'><font style='color:#FF6600; font-weight:bold;'>" . CHtml::link(CHtml::encode($data["female"]), '#', array('id' => 'open-modal', 'title' => CHtml::encode($data["fremarks"]), 'class' => 'tooltipster', 'data-toggle' => 'modal', 'data-target' => '#editGermplasmNameModal', 'data-id' => $data["female"])) . "</font></div>";
                            }
                        },
                                
                    ),
                    array(
                        'header' => 'Male Parent',
                        'name' => 'male',
                        'type' => 'raw',
                        'value' => function ($data) {

                            if (strcmp(CHtml::encode($data["mremarks"]), "in standardized format") == 0) {

                                $your_array = array();
                                $your_array = explode("#", CHtml::encode($data["mgid"]));
                                $your_array = implode("<br>", $your_array);
                                $mgid = $your_array;
                                return "<b>" . CHtml::encode($data["male"]) . "</b>" . "" . $mgid . "";
                            } else {
                                //echo CHtml::hiddenField('hiddenMid',CHtml::encode($data["male"]));
                                //Yii::app()->createUrl("site/editGermplasm", array("germplasm" => $data["male"], "error" => $data["mremarks"]))
                                echo "<input type='hidden' class='" . $data["male"] . "' name='male' value='" . $data["male"] . "'>";
                                echo "<input type='hidden' class='" . $data["male"] . "' name='mremarks' value='" . $data["mremarks"] . "'>";
                                return "<div class='j'><font style='color:#FF6600; font-weight:bold;'>" . CHtml::link(CHtml::encode($data["male"]), '#', array('id' => 'open-modal', 'title' => CHtml::encode($data["mremarks"]), 'class' => 'tooltipster', 'data-toggle' => 'modal', 'data-target' => '#editGermplasmNameModal', 'data-id' => $data["male"])) . "</font></div>";
                            }
                            echo CHtml::hiddenField('hiddenMid', CHtml::encode($data["male"]));
                        },
                    ),
                                
                    array(
                        'header' => 'Date of Creation',
                        'name' => 'date',
                        'type' => 'raw',
                        'value' => 'CHtml::encode($data["date"])',
                        'filter' => CHtml::textField('FilterPedigreeForm2[gid]', isset($_GET['FilterPedigreeForm2']['date]']) ? $_GET['FilterPedigreeForm2']['date'] : ''),
                    ),
                ),
            ));
        }
        ?>
    </div>
    <div id="standardized_table">

        <br/>
        <h4 style=" border-bottom: 0px solid #999;text-align: left;">Table 2. List of sorted germplasm in <b>standardized</b> and <b>non</b>-<b>standardized</b> format.</h4> 
        <i><p ><strong>Note:</strong>&nbsp; 
                Germplasm names not in standardized format are in red color.Hover the mouse over the germplasm names to see the error and click to correct it.
            </p></i>

        <br>
        <?php
        $this->widget('ext.selgridview.BootSelGridView', array(
            'id' => 'pedigreeGrid',
            'dataProvider' => $dataProvider,
            'beforeAjaxUpdate' => 'js:
                function (id, options) {
                    options.data = {
                        list: $("#list").val(),
                        locationID: $("#location").val(),
                        location: $("#location").val(),
                        next: 1
                    };
                    options.type = "post";
                }
            ',
            'filter' => $filtersForm,
            'selectableRows' => 2,
            'enablePagination' => true,
            'columns' => array(
                array(
                    'id' => 'selectedIds', //checked[]
                    'class' => 'CCheckBoxColumn',
                ),
                array(
                    'header' => 'Cross Name',
                    'value' => 'CHtml::encode($data["nval"])',
                    'name' => '',
                    //'filter'=>CHtml::textField('FilterPedigreeForm[nval]'),
                    'filter' => CHtml::textField('FilterPedigreeForm[nval]', isset($_GET['FilterPedigreeForm']['nval]']) ? $_GET['FilterPedigreeForm']['nval'] : ''),
                    'htmlOptions' => array(
                        'style' => 'width:50px;',
                    )
                ),
                array(
                    'header' => 'Female Parent',
                    'name' => 'female',
                    'type' => 'raw',
                    /* 'value'=>'CHtml::link( CHtml::encode($data["female"]),
                      Yii::app()->createUrl( "site/editGermplasm", array("germplasm"=>$data["female"]) ))', */
                    'value' => function ($data) {
                        if (strcmp(CHtml::encode($data["fremarks"]), "in standardized format") == 0) {
                            $your_array = array();
                            $your_array = explode("#", CHtml::encode($data["fgid"]));
                            $your_array = implode("<br>", $your_array);
                            $fgid = $your_array;
                            return "<b>" . CHtml::encode($data["female"]) . "</b>" . "" . $fgid . "";
                        } else {
                            //return "<font style='color:#FF6600; font-weight:bold;'>".CHtml::encode($data["female"])."</font>";


                            echo "<input type='hidden' class='" . $data["female"] . "' name='female' value='" . $data["female"] . "'>";
                            echo "<input type='hidden' class='" . $data["female"] . "' name='fremarks' value='" . $data["fremarks"] . "'>";
                            return "<div class='j'><font style='color:#FF6600; font-weight:bold;'>" . CHtml::link(CHtml::encode($data["female"]), '#', array('id' => 'open-modal', 'title' => CHtml::encode($data["fremarks"]), 'class' => 'tooltipster', 'data-toggle' => 'modal', 'data-target' => '#editGermplasmNameModal', 'data-id' => $data["female"])) . "</font></div>";

                            //echo CHtml::hiddenField('hiddenFid',CHtml::encode($data["female"]));
                        }
                    },
                ),
                array(
                    'header' => 'Male Parent',
                    'name' => 'male',
                    'type' => 'raw',
                    'value' => function ($data) {
                        if (strcmp(CHtml::encode($data["mremarks"]), "in standardized format") == 0) {

                            $your_array = array();
                            $your_array = explode("#", CHtml::encode($data["mgid"]));
                            $your_array = implode("<br>", $your_array);
                            $mgid = $your_array;
                            return "<b>" . CHtml::encode($data["male"]) . "</b>" . "" . $mgid . "";
                        } else {
                            echo "<input type='hidden' class='" . $data["male"] . "' name='male' value='" . $data["male"] . "'>";
                            echo "<input type='hidden' class='" . $data["male"] . "' name='mremarks' value='" . $data["mremarks"] . "'>";
                            return "<div class='j'><font style='color:#FF6600; font-weight:bold;'>" . CHtml::link(CHtml::encode($data["male"]), '#', array('id' => 'open-modal', 'title' => CHtml::encode($data["mremarks"]), 'class' => 'tooltipster', 'data-toggle' => 'modal', 'data-target' => '#editGermplasmNameModal', 'data-id' => $data["male"])) . "</font></div>";
                        }
                        echo CHtml::hiddenField('hiddenMid', CHtml::encode($data["male"]));
                    },
                ),
                            
                    array(
                        'header' => 'Date of Creation',
                        'name' => 'date',
                        'type' => 'raw',
                        'value' => 'CHtml::encode($data["date"])',
                        'filter' => CHtml::textField('FilterPedigreeForm2[gid]', isset($_GET['FilterPedigreeForm2']['date]']) ? $_GET['FilterPedigreeForm2']['date'] : ''),
                    ),
            ),
        ));
        ?>

        <div class="assign">


            <?php
            echo CHtml::hiddenField('list', json_encode($list));
            echo CHtml::hiddenField('location', $locationID);
            echo CHtml::hiddenField('locationID', $locationID);

            $url = Yii::app()->createUrl('site/assignGID');
            $this->widget('bootstrap.widgets.TbButton', array(
                'type' => 'primary',
                'label' => 'AssignGID',
                //'url' =>array('site/assignGID'),
                'htmlOptions' => array(
                    'onclick' => 'js:
                        var selected = $("#pedigreeGrid").selGridView("getAllSelection");
                        $("#germplasm-id").val(selected);
                        $("#submit-btn").click();
                        /*var selected = $("#pedigreeGrid").selGridView("getAllSelection"); 
                        alert(selected);
                        $.ajax({
                                type: "POST",
                                data: {selectedIds:selected},
                                url: "' . $url . '",
                                success: function(response){
                                        //window.location="' . $url . '";
                                }
                        });*/
                ',
                ),
            ));

            echo CHtml::textField('Germplasm[gid]', '', array(
                'id' => 'germplasm-id',
                'form' => 'assign-gid-form',
                'class' => 'hidden',
            ));

            echo CHtml::submitButton('Submit', array(
                'id' => 'submit-btn',
                'class' => 'hidden',
                'form' => 'assign-gid-form',
                    //'onclick' => 'js: alert("hello");',
            ));
            ?>
            <!--
             <input type="hidden" id ="list1" name="list1" value="">
             <input type="hidden" id ="location1" name="location" value="">
            -->
        </div>
    </div>
    <?php $this->endWidget(); ?>
</body>
<script type="text/javascript">
    function storeLocal() {
        if ('localStorage' in window && window['localStorage'] != null) {
            try {
                console.log(JSON.stringify(<?php echo json_encode($list); ?>));
                localStorage.setItem('list', JSON.stringify(<?php echo json_encode($list); ?>));
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

    $(document).ready(function() {

        //triggers  the activity loading indicator
        $('#submit-btn').click(function() {
            var selected = $.fn.yiiGridView.getSelection("pedigreeGrid");
            /*if (!selected.length)
             {
             alert('Please select at least one row');
             return false;
             }
             else
             {*/
            $('#screen').css({opacity: 0.4, 'width': $(document).width(), 'height': $(document).height()});
            $('body').css({'overflow': 'hidden'});
            $('#ajax-loading-indicator').css({'display': 'block'});
            //}
        });
    });

    //opens the modal from another page
    $(document).on("click", "#open-modal", function() {
        var id = $(this).data("id");
        var arr = document.getElementsByClassName(id);
        var arr_val = new Array();
        arr_val.length = 0;
        for (var i = 0; i < arr.length; i++) {
            arr_val.push(arr[i].value);
        }
        //alert(female);
        //alert(arr_val);
        var germplasm1 = arr_val[0];
        var error1 = arr_val[1];

        $.ajax({
            cache: false,
            type: 'POST',
            url: '<?php echo Yii::app()->createUrl("site/editGermplasm"); ?>',
            data: {germplasmName: germplasm1, error: error1},
            success: function(data) {
                $("#editGermplasmNameModal").html(data);
            }
        });


    });

</script>
