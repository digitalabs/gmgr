<?php

// import required classes to my widget
Yii::import('bootstrap.widgets.TbExtendedGridView');
Yii::import('bootstrap.widgets.TbExtendedFilter');
 
class BimExtendedGridView extends TbExtendedGridView
{
	/**
	* We need this attribute in order to fire the saved filter.
	* In fact, you could remove its requirement from TbExtendedFilter but
	* we thought is better to provide 'less' magic.
	*/
    public $class;
	public $redirectRoute;
    public $persistVars;
    public $model = null;
    public $dataClass;
    public $bim = array();
    
    protected $className;
    protected $defaultColumns = array();
    protected $customColumns = array();
    protected $processedColumns = array();
    protected $headers = array();
    protected $sessionId = null;
    protected $moduleId;
    protected $controllerId;
    
    public function init()
    {
        if (isset($_GET['pageSize']))
        {
            Yii::app()->user->setState('pageSize', (int)$_GET['pageSize']);
            Yii::import('bootstrap.widgets.BimExtendedFilter');
            $cleanedUrl = BimExtendedFilter::cleanUrlQuery(Yii::app()->request->url, array('pageSize'));
            Yii::app()->request->redirect($cleanedUrl);
        }
        
        $defaultProperties = array(
            'redirectRoute' => CHtml::normalizeUrl(''),
            'type' => 'bordered condensed hover',
            'template' => "{pager}\n{summary}\n{items}\n{pager}",
            //'enableHistory' => TRUE,
        );
        
        foreach ($defaultProperties as $key => $value)
            $this->$key = $value;
        
        $customProperties = array(
            'ajaxUpdate' => false,
            'ajaxUrl' => CHtml::normalizeUrl(''),
        );
        
        foreach ($customProperties as $key => $value)
            if (!isset($this->$key))
               $this->$key = $value;
        
        parent::init();
    }
    
    public function registerClientScript()
    {
        parent::registerClientScript();
        
        if (isset($this->class))
        {
            $classArr = explode('.', $this->class);
            $this->className = $classArr[count($classArr) - 1];
            
            if ($this->className == 'BootSelGridView')
            {
                // BootSelGridView's init()
                $dpId = $this->dataProvider->getId();
                if(empty($dpId)) $dpId = $this->id;
                $selVar = (empty($dpId)) ? 'sel' : $dpId.'_sel';
                
                $baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.selgridview.assets'));
                
                $cs=Yii::app()->getClientScript();
                $cs->registerScriptFile($baseScriptUrl.'/jquery.selgridview.js',CClientScript::POS_END);
                $cs->registerScript($this->className.'#'.$this->id.'-sel', "jQuery('#".$this->id."').selGridView(".CJavaScript::encode(array('selVar'=>$selVar)).");");
            }
        }
    }
    
	public function renderTableHeader()
	{
		if(!$this->hideHeader)
		{
			echo "<thead>\n";
            
            $this->renderToggleColumns();
            
			// Heads up! We are going to display our filter here
			$this->renderExtendedFilter();
			if($this->filterPosition===self::FILTER_POS_HEADER)
				$this->renderFilter();
            
			echo "<tr>\n";
			foreach($this->columns as $column)
				$column->renderHeaderCell();
			echo "</tr>\n";
            
			if($this->filterPosition===self::FILTER_POS_BODY)
				$this->renderFilter();
            
			echo "</thead>\n";
		}
		elseif($this->filter!==null && ($this->filterPosition===self::FILTER_POS_HEADER || $this->filterPosition===self::FILTER_POS_BODY))
		{
			echo "<thead>\n";
			// Heads up! We are going to display our filter here
			$this->renderExtendedFilter();
			$this->renderFilter();
			echo "</thead>\n";
		}
	}

	protected function renderExtendedFilter()
	{
		// at the moment it only works with instances of CActiveRecord
		if(!$this->filter instanceof CActiveRecord)
			return false;
        
		$extendedFilter =  Yii::createComponent(array(
			'class' => 'bootstrap.widgets.BimExtendedFilter',
			'model' => $this->filter,
			'grid' => $this,
			'redirectRoute' => $this->redirectRoute, //ie: array('/report/index', 'ajax'=>$this->id)
            'persistVars' => $this->persistVars,
		));
 
		$extendedFilter->init();
		$extendedFilter->run();
	}
    
    public function initColumns()
    {
        $this->processModel();
        $this->processDataClass();
        $this->processSessionId();
        $this->processColumns();
        
        if (!empty($this->defaultColumns))
        {
            foreach ($this->columns as $i => $column)
            {
                $isColumnArray = is_array($column);
                
                if (isset($this->processedColumns[$i]))
                    $name = $this->processedColumns[$i]['name'];
                elseif ($isColumnArray)
                    $name = isset($column['name']) ? $column['name'] : 'deviantColumn';
                else
                    $name = $column;
                
                $name = strpos($name, '.') > 0 ? substr($name, strpos($name, '.') + 1) : $name;
                
                if ($name != 'deviantColumn')
                    $class = ' gridCell bim-'.$name.' '.(!empty($this->customColumns[$name]) ? '' : 'hide').' '.(!empty($this->defaultColumns[$name]) ? 'defaultVisible' : '');
                else
                    $class = '';

                if ($isColumnArray)
                {
                    if (!isset($column['class']))
                        $this->columns[$i]['class'] = 'bootstrap.widgets.BimDataColumn';
                    
                    foreach (array('htmlOptions', 'headerHtmlOptions', 'filterHtmlOptions') as $place)
                    {
                        if (isset($column[$place]) && isset($column[$place]['class']))
                        {
                            $this->columns[$i][$place]['class'] .= $class;
                            
//                            if ($place == 'filterHtmlOptions')
//                            {
//                                if (!isset($column[$place]['rel']))
//                                {
//                                    $this->columns[$i][$place]['rel'] = 'tooltip';
//                                    $this->columns[$i][$place]['title'] = 'Filter '.$this->headers[$name];
//                                }
//                            }
                        }
                        else
                            $this->columns[$i][$place]['class'] = $class;
                    }
                }
                else
                {
                    $this->columns[$i] = array(
                        'name' => $name,
                        'class' => 'bootstrap.widgets.BimDataColumn',
                        'htmlOptions' => array(
                            'class' => $class,
                        ),
                        'headerHtmlOptions' => array(
                            'class' => $class,
                        ),
                        'filterHtmlOptions' => array(
                            'class' => $class,
                        ),
                        'type' => 'raw',
                    );
                }
            }
        }
        else
        {
            foreach ($this->columns as $i => $column)
            {
                if (is_array($column) && !isset($column['class']))
                    $this->columns[$i]['class'] = 'bootstrap.widgets.BimDataColumn';
            }
        }
        
		parent::initColumns();
        
		if ($this->responsiveTable)
			$this->writeResponsiveCss();
    }
    
    protected function renderToggleColumns()
    {
        if (!empty($this->customColumns))
        {
            echo $this->render('bootstrap.views.bim.customizeGrid', array(
                'model' => $this->model,
                'columns' => $this->defaultColumns,
                'customColumns' => $this->customColumns,
                'gridId' => $this->id,
                'sessionId' => $this->sessionId,
                'headers' => $this->headers,
                'dataClass' => $this->dataClass,
            ), TRUE);
        }
    }
    
    protected function processModel()
    {
        if (empty($this->model))
        {
            if (!empty($this->dataProvider->model))
                $this->model = $this->dataProvider->model;
        }
    }
    
    protected function processDataClass()
    {
        if (empty($this->dataClass))
        {
            if (!empty($this->dataProvider->model))
                $this->dataClass = get_class($this->dataProvider->model);
            else
                $this->dataClass = 'CDataProvider';
        }
    }
    
    protected function processSessionId()
    {
        $this->controllerId = get_class($this->controller);
        $this->moduleId = get_class($this->controller->module);
        $this->sessionId = $this->moduleId.'-'.$this->controllerId.'-'.$this->getClassName().'-'.$this->id;
    }
    
    protected function getClassName()
    {
        return is_object($this->model) ? get_class($this->model) : $this->model;
    }
    
    protected function processColumns()
    {
        if (empty($this->model))
            return;
        
        if (!empty($this->bim['columns']))
        {
            $this->defaultColumns = $this->bim['columns'];
        }
        elseif (!empty($this->model->columns) and !method_exists($this->model, 'getColumns'))
        {
            $columns = $this->model->columns;
            
            if (count(array_filter($columns, 'is_array')) > 0)
            {
                foreach ($columns as $item)
                    $this->defaultColumns = CMap::mergeArray($this->defaultColumns, is_array($item) ? $item : array($item));
            }
            else
                $this->defaultColumns = $columns;
        }
        else
        {
            $columns = array();
            
            foreach ($this->columns as $i => $column)
            {
                if ((isset($column['class']) && in_array($column['class'], array('bootstrap.widgets.BimButtonColumn', 'bootstrap.widgets.TbButtonColumn', 'CCheckBoxColumn'))) || (isset($column['skipToggle']) && $column['skipToggle'] == TRUE))
                    continue;
                
                if (is_string($column))
                {
                    $column = array(
                        'name' => $column,
                        'type' => 'raw',
                    );
                }
                
                $name = isset($column['name']) ? $column['name'] : (isset($column['header']) ? 'bim-toggleColumn_'.$i : 'bim-deviantColumn_'.$i);
                
                if (!empty($name))
                {
                    $name = strpos($name, '.') > 0 ? substr($name, strpos($name, '.') + 1) : $name;
                    $this->defaultColumns[$name] = true;
//                        $this->defaultColumns[$name] = !(isset($column['class']) && in_array($column['class'], array('bootstrap.widgets.BimButtonColumn', 'bootstrap.widgets.TbButtonColumn', 'CCheckBoxColumn')));
//                        $this->processedColumns[$indices[$name]]['name'] = $name;
                    $this->headers[$name] = isset($column['header']) ? $column['header'] : $this->model->getAttributeLabel($name);
                }
                else
                {
                    //$this->headers[$name] = isset($column['header']) ? $column['header'] : $this->model->getAttributeLabel($name);
                    $this->headers[$name] = isset($column['header']) ? $column['header'] : $this->model->getAttributeLabel($name);
                }
                
                //$columns[$name] = $column;
                $this->processedColumns[$i]['name'] = $name;
//                $indices[$name] = $i;
            }
            
//            $columns = CHtml::listData($this->columns, 'name', function($item) {
//                return $item;
//            });
            
            /*if (!empty($columns))
            {
                foreach ($columns as $name => $column)
                {
                    if (!empty($name))
                    {
                        $name = strpos($name, '.') > 0 ? substr($name, strpos($name, '.') + 1) : $name;
                        $this->defaultColumns[$name] = true;
//                        $this->defaultColumns[$name] = !(isset($column['class']) && in_array($column['class'], array('bootstrap.widgets.BimButtonColumn', 'bootstrap.widgets.TbButtonColumn', 'CCheckBoxColumn')));
//                        $this->processedColumns[$indices[$name]]['name'] = $name;
                        $this->headers[$name] = isset($column['header']) ? $column['header'] : $this->model->getAttributeLabel($name);
                    }
                    else
                    {
                        //$this->headers[$name] = isset($column['header']) ? $column['header'] : $this->model->getAttributeLabel($name);
                        $this->headers[$name] = isset($column['header']) ? $column['header'] : $this->model->getAttributeLabel($name);
                    }
                }
            }*/
        }
        
        /*else
        {
            $attributes = $this->model->attributeNames();
            
            foreach ($attributes as $attribute)
                $this->defaultColumns[$attribute] = TRUE;
        }*/
        
        if (!empty(Yii::app()->session[$this->sessionId]))
        {
            $sessionBrowser = Yii::app()->session[$this->sessionId]['browser'];
            
            if (count(array_diff(array_keys($this->defaultColumns), array_keys($sessionBrowser['columns']))) > 0
                || count(array_diff(array_keys($this->defaultColumns), array_keys($sessionBrowser['customColumns']))) > 0
            )
            {
                unset(Yii::app()->session[$this->sessionId]);
                Yii::app()->request->redirect(CHtml::normalizeUrl(''));
            }
            
            $this->customColumns = $sessionBrowser['customColumns'];
        }
        else
        {
            $this->customColumns = $this->defaultColumns;
            
            Yii::app()->session[$this->sessionId] = array(
                'browser' => array(
                    'customColumns' => $this->customColumns,
                    'columns' => $this->defaultColumns,
                ),
            );
        }
    }
}

/*Yii::import('bootstrap.widgets.TbExtendedGridView');

class BimExtendedGridView extends TbExtendedGridView
{
    public $properties = array();
    
    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    public function run()
    {
        $classVars = get_class_vars('TbExtendedGridView');
        
        foreach ($classVars as $key => $classVar)
        {
            if (isset($this->$key))
            {
                $this->properties[$key] = $this->$key;
                if (is_string($this->$key))
                    var_dump($key.'=>'.$this->$key.'<br/>');
            }
        }
        
//        $this->widget('TbExtendedGridView', $this->properties);
    }
}*/