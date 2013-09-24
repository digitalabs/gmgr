<?php

Yii::import('bootstrap.widgets.TbButtonColumn');

/**
 * Bootstrap button column widget.
 * Used to set buttons to use Glyphicons instead of the defaults images.
 */
class BimButtonColumn extends TbButtonColumn
{
    public $pageSizeSelector = TRUE;
    public $skipToggle = FALSE;
    public $value;
    public $pageSizeOptions = array(
        5 => 5,
        10 => 10,
        20 => 20,
        30 => 30,
        40 => 40,
        50 => 50,
        100 => 100
    );

    public function renderDataCellContent($row, $data)
    {
        if (isset($this->value))
        {
            switch (strtolower($this->value))
            {
                case 'rownumber':
                    echo $this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1);
                    break;
            }
        }
        else
            parent::renderDataCellContent($row, $data);
    }
    
	public function renderHeaderCellContent()
    {
//        parent::renderHeaderCellContent();
//        echo 'Actions';
//        
//        return;
        if ($this->pageSizeSelector)
        {
            $clientScript = Yii::app()->clientScript;
            $baseUrl = Yii::app()->baseUrl;
            
            $clientScript->registerScriptFile($baseUrl.'/js/jquery.blockUI.js', CClientScript::POS_BEGIN);
            $clientScript->registerScript('initPageSize', <<<EOD
                var message = $("#domMessage");
                
                if (!message.length)
                    message = $('<div id="domMessage" class="hide"><div id="image"><img src="{$baseUrl}/images/134.gif"/></div><div id="load"><h1>Loading...</h1></div></div>');
                
                $('.change-pagesize').live('change', function() {
                    $.blockUI({
                        message: message,
                        css: {
                            border: 'none',
                            padding: '15px',
                            backgroundColor: '#000',
                            '-webkit-border-radius': '10px',
                            '-moz-border-radius': '10px',
                            opacity: .5,
                            color: '#fff'
                        }
                    });
                    
                    $.fn.yiiGridView.update('{$this->grid->id}', {
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
            
            /*if (isset($_REQUEST['pageSize'])) {
                Yii::app()->user->setState('pageSize', (int)$_REQUEST['pageSize']);
            }*/
            
            /*$this->grid->getController()->widget('bootstrap.widgets.TbSelect2',array(
                'name' => 'pageSize',
                'value' => isset($_GET['pageSize']) ? (int)$_GET['pageSize'] : Yii::app()->params['defaultPageSize'],
                'data' => $this->pageSizeOptions,
                'options' => array(
                    'width' => '75px',
                ),
                'htmlOptions' => array(
                    'class' => 'change-pagesize',
                ),
            ));*/
            
            echo CHtml::dropDownList(
                'pageSize',
                Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
                //isset($_GET['pageSize']) ? (int)$_GET['pageSize'] : Yii::app()->params['defaultPageSize'],
                $this->pageSizeOptions,
                array(
                    'class'=>'change-pagesize',
                    'style'=>'width: 50px; margin: 0px; font-size: 0.95em;',
                    'title' => TblTooltip::model()->findByAttributes(array('tooltip_key' => "pagesize"))->tooltip,
                    'rel' => 'tooltip',
                )
            );
        }
    }
}
