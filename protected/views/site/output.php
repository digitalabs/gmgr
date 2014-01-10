<!--div to grey out the screen while loading indicator is on-->
<body onload="storeLocal()">

    <div id='screen'>
    </div>
    <span id="ajax-loading-indicator">
    </span>
    <!---End for loading indicators-->
	
	<!--****For edit germplasm modal dialog-->
	<div id="editModalDialog" class="modal hide fade in" style="display: none;"></div>
	<!--****End of edit germplasm modal dialog-->
    
    <?php
    $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type' => 'horizontal',
        'id' => 'assign-gid-form',
        'action' => array('/site/assignGID'),
        'htmlOptions' => array('class' => 'well well-small'),
    ));
	
	if(isset($dataProvider2)){
    ?>
    <h4 style=" border-bottom: 0px solid #999;text-align: left;">Table 1. List of germplasm <b>not</b> in <b>standardized</b> format.</h4> 
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

					'header' => 'Date of Creation',
					'name' => 'date',
					'type' => 'raw',
					'value' => 'CHtml::encode($data["date"])',
				'filter' => CHtml::textField('FilterPedigreeForm2[gid]', isset($_GET['FilterPedigreeForm2']['date]']) ? $_GET['FilterPedigreeForm2']['date'] : ''),
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
							return "<b>".CHtml::encode($data["female"]) . "</b>" . "" . $fgid . "";
						} else {
							//return "<font style='color:#FF6600; font-weight:bold;'>".CHtml::encode($data["female"])."</font>";

							return "<div class='j'><font style='color:#FF6600; font-weight:bold;'>" . CHtml::link(CHtml::encode($data["female"]), Yii::app()->createUrl("site/editGermplasm", array("germplasm" => $data["female"], "error" => $data["fremarks"])), array('title' => CHtml::encode($data["fremarks"]), 'class' => 'tooltipster', 'data-toggle'=>'modal','data-target'=>'editModalDialog')) . "</font></div>";

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
							return "<b>".CHtml::encode($data["male"]) . "</b>" . "" . $mgid . "";
						} else {
							//echo CHtml::hiddenField('hiddenMid',CHtml::encode($data["male"]));
							return "<div class='j'><font style='color:#FF6600; font-weight:bold;'>" . CHtml::link(CHtml::encode($data["male"]), Yii::app()->createUrl("site/editGermplasm", array("germplasm" => $data["male"], "error" => $data["mremarks"])), array('title' => CHtml::encode($data["mremarks"]), 'class' => 'tooltipster')) . "</font></div>";
						}
						echo CHtml::hiddenField('hiddenMid', CHtml::encode($data["male"]));
					},
				)
			),
		));
	}
    ?>
    
    <div id='table1'>
        <br/>
         <h4 style=" border-bottom: 0px solid #999;text-align: left;">Table 2. List of sorted germplasm in <b>standardized</b> and <b>non</b>-<b>standardized</b> format.</h4> 
        <i><p ><strong>Note:</strong>&nbsp; 
                Germplasm names not in standardized format are in red color.Hover the mouse over the germplasm names to see the error and click to correct it.
            </p></i>

        <?php
//Dropdown Pagination
        $pageSize = Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']);
        ?>
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
                    'header' => 'GID',
                    'value' => 'CHtml::encode($data["gid"])',
                    'name' => 'gid',
                    //'filter'=>CHtml::textField('FilterPedigreeForm[gid]'),
                    'filter' => CHtml::textField('FilterPedigreeForm[gid]', isset($_GET['FilterPedigreeForm']['gid]']) ? $_GET['FilterPedigreeForm']['gid'] : ''),
                ),
                array(
                    'header' => 'Female Parent',
                    'name' => 'female',
                    'type' => 'raw',
                    /* 'value'=>'CHtml::link( CHtml::encode($data["female"]),
                      Yii::app()->createUrl( "site/editGermplasm", array("germplasm"=>$data["female"]) ))', */
                    'value' => function ($data) {
                        if (strcmp(CHtml::encode($data["fremarks"]), "in standardized format") === 0) {

                            return CHtml::encode($data["female"]);        // CHtml::hiddenField('hiddenFid',CHtml::encode($data["female"]));   
                        } else {
                            //return "<font style='color:#FF6600; font-weight:bold;'>".CHtml::encode($data["female"])."</font>";

                            return "<div class='j'><font style='color:#FF6600; font-weight:bold;'>" . CHtml::link(CHtml::encode($data["female"]), Yii::app()->createUrl("site/editGermplasm", array("germplasm" => $data["female"], "error" => $data["fremarks"])), array('title' => CHtml::encode($data["fremarks"]), 'class' => 'tooltipster')) . "</font></div>";

                            //echo CHtml::hiddenField('hiddenFid',CHtml::encode($data["female"]));
                        }
                    },
                ),
                array(
                    'header' => 'Male Parent',
                    'name' => 'male',
                    'type' => 'raw',
                    'value' => function ($data) {
                        if (strcmp(CHtml::encode($data["mremarks"]), "in standardized format") === 0) {
                            //echo CHtml::hiddenField('hiddenMid',CHtml::encode($data["male"]));
                            return CHtml::encode($data["male"]);
                        } else {
                            //echo CHtml::hiddenField('hiddenMid',CHtml::encode($data["male"]));
                            return "<div class='j'><font style='color:#FF6600; font-weight:bold;'>" . CHtml::link(CHtml::encode($data["male"]), Yii::app()->createUrl("site/editGermplasm", array("germplasm" => $data["male"], "error" => $data["mremarks"])), array('title' => CHtml::encode($data["mremarks"]), 'class' => 'tooltipster')) . "</font></div>";
                        }
                        echo CHtml::hiddenField('hiddenMid', CHtml::encode($data["male"]));
                    },
                ),
                array(
                    'header' => 'New GID',
                    'type' => 'raw',
                    //'value'=>'CHtml::encode($data["mgid"])'
                    'value' => function($data) {
                        $your_array = array();
                        $your_array = explode("#", CHtml::encode($data["fgid"]));
                        $your_array = implode("\n", $your_array);
                        $fgid = $your_array;

                        $your_array = array();
                        $your_array = explode("#", CHtml::encode($data["mgid"]));
                        $your_array = implode("\n", $your_array);
                        $mgid = $your_array;

                        return "<pre>" . $fgid . "</pre><pre>" . $mgid . "</pre>";
                    }
                ),
            ),
        ));
        ?>

    </div>

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

        $this->endWidget();
        ?>
       <!--
        <input type="hidden" id ="list1" name="list1" value="">
        <input type="hidden" id ="location1" name="location" value="">
       -->
    </div>

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
            if (!selected.length)
            {
                alert('Please select at least one row');
                return false;
            }
            else
            {
                $('#screen').css({opacity: 0.4, 'width': $(document).width(), 'height': $(document).height()});
                $('body').css({'overflow': 'hidden'});
                $('#ajax-loading-indicator').css({'display': 'block'});
            }


        });


        //checkboxes
// $("#ajaxSubmit").click(function(){
        //$(".selection").show();
        //alert( $.fn.yiiGridView.getSelection("pedigreeGrid"));
        //var arr = $("#pedigreeGrid").selGridView("getAllSelection");
        //alert(arr);

        //});
        /* function selectAll(){
         var inputs = document.getElementsByTagName("input");
         var checkboxes = [];
         for (var i = 0; i < inputs.length; i++) {
         if (inputs[i].type == "checkbox") {
         inputs[i].checked = true;
         }
         }
         }*/
    });
</script>
