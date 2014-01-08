<body onload="storeLocal()">
    <form action="" method="post" id='importFileDisplay-rfrsh'>
        <input type="hidden" name="refresh" value="true">
        <input type="hidden" name="location" id="location" value="<?php echo $locationID; ?>">
        <input type="hidden" name="list" id="list" value="">
    </form>    
    <br>
    <h3>Germplasm List</h3>
    <p >
        <i><strong>Note:</strong>&nbsp; 
            Germplasm names <b>not</b> in <b>standardized</b> format are in <b>red color</b>.
        </i>
        <br><br>
    </p>
    <!--div to grey out the screen while loading indicator is on-->
    <div id='screen'>
    </div>
    <span id="ajax-loading-indicator">
    </span>
    <!---End for loading indicators-->

    <?php
    /** @var BootActiveForm $form */
    /* $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
      'id' => 'standardLink',
      'type' => 'horizontal',
      'htmlOptions' => array('class' => 'well'),
      ));
     */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type' => 'horizontal',
        'id' => 'standardLink',
        'action' => array('/site/output'),
    ));

//echo CHtml::beginForm();
//<input type="hidden" name="standardize" value="yes" />
    echo CHtml::hiddenField('standardize', 'yes');
    echo CHtml::hiddenField('list', json_encode($list));
    echo CHtml::hiddenField('locationID', $locationID);

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'label' => 'Click to Standardize Germplasm',
        'id' => 'btnStandard',
        'type' => 'primary',
    ));
    ?>


    <div id="GermplasmList" class="GermplasmList">
    </div>    
    <?php
//call output table
//include( dirname(__FILE__). "/output.php");
    ?>
    <?php
// echo "<div id='table1'>";
    $this->widget('ext.selgridview.BootSelGridView', array(
        'id' => 'pedigreeGrid',
        'dataProvider' => $dataProvider,
        'beforeAjaxUpdate' => 'js:
                function (id, options) {
                    options.data = {
                        list: $("#list1").val(),
                        location: $("#location1").val(),
                        refresh: 1
                    };
                    options.type = "post";
                }
            ',
        'filter' => $filtersForm,
        'selectableRows' => 10,
        'enablePagination' => true,
        'columns' => array(
            array(
                'header' => 'Cross Name',
                'name' => '',
                'type' => 'raw',
                'value' => 'CHtml::encode($data["nval"])',
                'filter' => CHtml::textField('FilterPedigreeForm[nval]', isset($_GET['FilterPedigreeForm']['nval]']) ? $_GET['FilterPedigreeForm']['nval'] : ''),
                'htmlOptions' => array(
                    'style' => 'width:50px;',
                )
            ),
            array(
                'header' => 'Date of Creation',
                'name' => 'date',
                'type' => 'raw',
                'value' => 'CHtml::encode($data["date"])'
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
                    }
                    else
                        return "<font style='color:#FF6600;'>" . CHtml::tag("span", array("title" => CHtml::encode($data["fremarks"]), "class" => "tooltipster"), CHtml::encode($data["female"])) . "</font>";
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
                    }
                    else
                        return "<font style='color:#FF6600; '>" . CHtml::tag("span", array("title" => CHtml::encode($data["mremarks"]), "class" => "tooltipster"), CHtml::encode($data["male"])) . "</font>";
                },
            ),
            
        ),
    ));

//echo "</div>";
    ?> 
    <input type="hidden" id ="location1" name="location" value="">
    <input type="hidden" id="list1" name="list" value="">
</body>

<script type="text/javascript">
    function storeLocal() {
        if ('localStorage' in window && window['localStorage'] != null) {
            try {
                localStorage.removeItem("locationID");
                localStorage.removeItem("list");

                var list = <?php echo json_encode($list); ?>;

                console.log(JSON.stringify(<?php echo json_encode($list); ?>));
                document.getElementById('list').value = list;
                localStorage.setItem('list', JSON.stringify(<?php echo json_encode($list); ?>));

                var locationID = document.getElementById('location').value;
                //var list=document.getElementById('list').value;
                //console.log("ss"+locationID);
                //console.log("sssw"+list);
                localStorage.setItem('locationID', locationID);
                   storeLocal1();
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
    $(document).ready(function() {
     
        var pop = function() {
            $('#screen').css({opacity: 0.4, 'width': $(document).width(), 'height': $(document).height()});
            $('body').css({'overflow': 'hidden'});
            $('#ajax-loading-indicator').css({'display': 'block'});
        }
        $('#btnStandard').click(pop);
    });
</script>
<?php
$this->endWidget();
//echo CHtml::endForm();
?>
