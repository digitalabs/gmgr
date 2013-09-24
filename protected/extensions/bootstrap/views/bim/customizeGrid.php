<?php
$unblockUI = 'js:
    var message = $("#domMessage");

    if (!message.length)
        message = $("<div id=\"domMessage\" class=\"hide\"><div id=\"image\"><img src=\"'.Yii::app()->baseUrl.'/images/134.gif\"/></div><div id=\"load\"><h1>Loading...</h1></div></div>");

    $.blockUI({
        message: message,
        css: {
            border: "none",
            padding: "15px",
            backgroundColor: "#000",
            "-webkit-border-radius": "10px",
            "-moz-border-radius": "10px",
            opacity: .5,
            color: "#fff"
        },
        overlayCSS: {
            backgroundColor: "#fff",
            opacity: 0,
        }
    });
';

$changeTooltip = '
    var $this = $(this);
    var $label = $("[for=\""+this.id+"\"]");
    
    var title = (this.checked ? "Hide column: " : "Show column: ") + $this.data("label");
    
    $this.attr("title", title);
    
    if ($this.data("tooltip") != null) {
        $this.tooltip("hide");
        $this.removeData("tooltip");
    }
    
    $this.tooltip({
        "title": title,
        "html": true
    });
    
    if ($label.length > 0)
    {
        $label.attr("title", title);
        
        if ($label.data("tooltip") != null) {
            $label.tooltip("hide");
            $label.removeData("tooltip");
        }

        $label.tooltip({
            "title": title,
            "html": true
        });
    }
'
?>

<div class="accordion" id="toggle-columns-<?php echo $gridId ?>-accdn">
    <div id="toggle-columns-checkbox-<?php echo $gridId ?>-container" class="in collapse" style="height: auto;">
        <div class="accordion-group">
            <div class="accordion-heading">
                <div class="accordion-toggle" data-toggle="collapse" data-parent="#toggle-columns-<?php echo $gridId ?>-accdn" href="#customizeGrid-<?php echo $gridId ?>-collapse" >
                    <a href="#" title="Choose what columns to display, change page size, and more." rel="tooltip" data-placement="right">
                        Customize browser
                    </a>
                </div>
            </div>
            <div id="customizeGrid-<?php echo $gridId ?>-collapse" class="accordion-body collapse">
                <div class="accordion-inner">
                    <div>
                        <span>
                            <?php if (isset($this->enablePagination) && $this->enablePagination != FALSE): ?>
                                <strong>Page size:</strong> <?php
                                /*
                                $this->widget('bootstrap.widgets.TbSelect2', array(
                                    'name' => 'pageSize',
                                    'asDropDownList' => false,
                                    'value' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
                                    'options' => array(
                                        'tags' => array(
                                            '5', '10', '20', '30', '40', '50', '100',
                                        ),
                                        'width' => '75px',
                                    ),
                                    'htmlOptions' => array(
                                        'class'=>'change-pagesize',
                                        'title' => TblTooltip::model()->findByAttributes(array('tooltip_key' => "pagesize"))->tooltip,
                                        'rel' => 'tooltip',
                                    ),
                                ));//*
                                //*/
                                echo CHtml::dropDownList(
                                    'pageSize',
                                    Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
                                    //isset($_GET['pageSize']) ? (int)$_GET['pageSize'] : Yii::app()->params['defaultPageSize'],
                                    array(
                                        5 => 5,
                                        10 => 10,
                                        20 => 20,
                                        30 => 30,
                                        40 => 40,
                                        50 => 50,
                                        100 => 100
                                    ),
                                    array(
                                        'class'=>'change-pagesize',
                                        'style'=>'width: 50px; margin: 0px; font-size: 0.95em;',
                                        'title' => TblTooltip::model()->findByAttributes(array('tooltip_key' => "pagesize"))->tooltip,
                                        'rel' => 'tooltip',
                                        'data-placement' => 'right',
                                    )
                                );//*/ ?>
                            </span> &nbsp;&nbsp;&nbsp;&nbsp;
                        <?php endif; ?>
                        
                        <span>
                            <strong>Show:</strong> <?php
                                echo CHtml::link('<span>Basic columns</span>', '#', array(
                                    'class' => 'btn btn-small btn-success',
                                    'onclick' => $unblockUI.CHtml::ajax(array(
                                        'type' => 'post',
                                        'url' => array('/bimGrid/updateColumnInGrid'),
                                        'data' => array(
                                            'BimGrid[browser][reset]' => TRUE,
                                            'BimGrid[browser][sessionId]' => $sessionId,
                                            'BimGrid[browser][model]' => $dataClass,
                                        ),
                                        'dataType' => 'json',
                                        'success' => 'js: function(response) {
                                            if (!response.success)
                                            {
                                                if (response.message)
                                                    bootbox.alert(response.message);

                                                return;
                                            }

                                            $("#'.$gridId.' .gridCell.defaultVisible").show();
                                            $("#'.$gridId.' .gridCell:not(.defaultVisible)").hide();
                                            $("#'.$gridId.' .checkBoxToggle.defaultChecked").attr("checked", true);
                                            $("#'.$gridId.' .checkBoxToggle:not(.defaultChecked)").removeAttr("checked");
                                        }',
                                        'complete' => 'js: function() {
                                            $.unblockUI();
                                            //$("#toggle-columns-accdn .accordion-toggle").click();
                                        }'
                                    )),
                                    'title' => 'Display basic columns only',
                                    'rel' => 'tooltip',
                                    'data-placement' => 'right',
                                ));

                                echo '&nbsp;&nbsp;';

                                echo CHtml::link('<strong>All columns</strong>', '#', array(
                                    'class' => 'btn btn-small',
                                    'onclick' => $unblockUI.CHtml::ajax(array(
                                        'type' => 'post',
                                        'url' => array('/bimGrid/updateColumnInGrid'),
                                        'data' => array(
                                            'BimGrid[browser][all]' => TRUE,
                                            'BimGrid[browser][sessionId]' => $sessionId,
                                            'BimGrid[browser][model]' => $dataClass,
                                        ),
                                        'dataType' => 'json',
                                        'success' => 'js: function(response) {
                                            if (!response.success)
                                            {
                                                if (response.message)
                                                    bootbox.alert(response.message);

                                                return;
                                            }
                                            
                                            $(".checkBoxToggle:not(:checked)").attr("checked", true).each(function() {
                                                '.$changeTooltip.'
                                            });
                                            $(".gridCell:not(:visible)").show();
                                        }',
                                        'complete' => 'js: function() {
                                            $.unblockUI();
                                        }'
                                    )),
                                    'title' => 'Display all columns',
                                    'rel' => 'tooltip',
                                    'data-placement' => 'right',
                                ));
                        ?>
                        </span>
                        <br/><br/>
                    </div>
                    <div> <?php
                        foreach ($customColumns as $name => $isChecked)
                        {
                            if (strpos($name, 'bim-deviantColumn_') !== FALSE)
                                continue;
                            
                            $checkBoxId = $gridId.'-'.$name.'-toggle-cbox';
                            $label = isset($headers[$name]) ? $headers[$name] : $model->getAttributeLabel($name);
                            $title = ($isChecked ? 'Hide column: ' : 'Show column: ').$label;
                            
                            echo CHtml::checkBox('toggleColumn', $isChecked, array(
                                'value' => $name,
                                'id' => $checkBoxId,
                                'data-column' => $label,
                                'class' => $gridId.' checkBoxToggle bim-'.$name.' '.((isset($columns[$name]) && $columns[$name] == true) ? 'defaultChecked' : ''),
                                'rel' => 'tooltip',
                                'title' => $title,
                                'data-label' => $label,
                                'onchange' => $changeTooltip.$unblockUI.CHtml::ajax(array(
                                    'type' => 'post',
                                    'url' => array('/bimGrid/updateColumnInGrid'),
                                    'data' => array(
                                        'BimGrid[browser][checked]' => 'js: this.checked',
                                        'BimGrid[browser][column]' => 'js: this.value',
                                        'BimGrid[browser][sessionId]' => $sessionId,
                                        'BimGrid[browser][model]' => $dataClass,
                                     ),
                                    'dataType' => 'json',
                                    'success' => 'js: function(response) {
                                        if (!response.success)
                                        {
                                            if (response.message)
                                                bootbox.alert(response.message);

                                            return;
                                        }
                                        
                                        $("#'.$gridId.' .gridCell.bim-'.$name.'").toggle();
                                    }',
                                    'complete' => 'js: function() {
                                        $.unblockUI();
                                    }'
                                )),
                            )).' '
                            .CHtml::label($label, $checkBoxId, array(
                                'style' => 'display:inline;',
                                'rel' => 'tooltip',
                                'title' => $title,
                                'data-label' => $label,
                            ));

                            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="domMessage" class="hide">
    <div id="image">
        <img src="<?php echo Yii::app()->baseUrl; ?>/images/134.gif"/>
    </div>
    <div id="load">
        <h1>Loading...</h1>
    </div>
</div>

<?php
$clientScript = Yii::app()->clientScript;
$baseUrl = Yii::app()->baseUrl;
$clientScript->registerScriptFile($baseUrl.'/js/jquery.blockUI.js', CClientScript::POS_BEGIN);
$clientScript->registerScript('initPageSize', <<<EOD
    var message = $('<div id="domMessage" class="hide"><div id="image"><img src="{$baseUrl}/images/134.gif"/></div><div id="load"><h1>Loading...</h1></div></div>');

    $('.change-pagesize').live('change', function() {
        $.blockUI({
            message: message,
            css: {
                border: "none",
                padding: "15px",
                backgroundColor: "#000",
                "-webkit-border-radius": "10px",
                "-moz-border-radius": "10px",
                opacity: .5,
                color: "#fff"
            },
            overlayCSS: {
                backgroundColor: "#fff",
                opacity: 0,
            }
        });

        $.fn.yiiGridView.update('{$gridId}', {
            data: {
                pageSize: $(this).val()
            },
            complete: function(jqXHR, status) {
                 $.unblockUI();
            }
        });
    });
EOD
, CClientScript::POS_READY);
?>