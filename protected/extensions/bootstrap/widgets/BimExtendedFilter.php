<?php

class BimExtendedFilter extends TbExtendedFilter
{
    public $lastUsedFilter = NULL;
    
    public $pageSizeVar = 'pageSize';
    
    public $pageVar = 'page';
    
    public $addlFilters = array();
    
    public $addlFilterKeys = array();
    
    public $hasAddlFilters = FALSE;
    
    public $hasLastUsedFilter = FALSE;
    
    public $persistVars = array();
    
    public $persistQuery = array();

    /**
	 *### .init()
	 *
	 * Widget initialization
	 * @throws CException
	 */
	public function init()
	{
		if (!$this->model instanceof CActiveRecord)
			throw new CException(Yii::t('zii', '"model" attribute must be an CActiveRecord type of component'));

		if (!$this->grid instanceof CGridView)
			throw new CException(Yii::t('zii', '"grid" attribute must be an CGridView type of component'));

		if (!$this->redirectRoute === null)
			throw new CException(Yii::t('zii', '"redirectRoute" cannot be empty'));

		$this->registry .= '-'.$this->grid->id;

		$this->jsonStorage = new BimJSONDbStorage();
		$this->jsonStorage->addRegistry($this->registry);

		$this->filteredBy = array_filter($this->model->getAttributes(), function($i){ return $i != null;});
        
        if (!$this->grid->ajaxUpdate)
        {
            $this->checkAdditionalFilters();
            $this->checkRequestRemovalFilter();
            $this->checkRequestFilters();
        }
        
        $this->processPersistVars();
        
        if (isset($_GET['clearFilter']))
        {
            // TODO persist vars on clear filter
            unset($_GET['clearFilter']);
            $this->redirectRoute = $this->cleanUrlQuery($this->redirectRoute, array('clearFilter'), CMap::mergeArray(array('lastUsed' => true), $_GET));
            $this->removeLastUsedFilter();
            $this->saveLastUsedFilter();
        }
        else
        {
            $this->hasLastUsedFilter = $this->getLastUsedFilter();
            $this->saveLastUsedFilter();
        }
        
		$this->registerClientScript();
        
        if (!isset($_GET['lastUsed']) and !isset($_GET['clearFilter']))
        {
            $flashes = Yii::app()->user->getFlashes();
            
            if (!empty($flashes))
            {
                foreach ($flashes as $key => $value)
                {
                    Yii::app()->user->setFlash($key, $value);
                }
            }
            
            $this->persistQuery['lastUsed'] = true;
            $this->redirectRoute = $this->cleanUrlQuery($this->redirectRoute, array(), CMap::mergeArray($this->persistQuery, $_GET));
            
            Yii::app()->controller->redirect($this->redirectRoute);
        }
	}
    
    protected function checkAdditionalFilters()
    {
        $dataProvider = $this->grid->dataProvider;
        
        $this->addlFilterKeys = array(
            $dataProvider->sort->sortVar,
            $dataProvider->pagination->pageVar,
            'pageSize',
        );
        
        foreach ($this->addlFilterKeys as $key)
        {
            if (isset($_REQUEST[$key]))
            {
                switch ($key)
                {
                    case 'pageSize':
                        if ($_REQUEST['pageSize'] != Yii::app()->params['defaultPageSize'])
                            $this->addlFilters[$key] = $_REQUEST[$key];
                    break;
                    
                    default:
                        $this->addlFilters[$key] = $_REQUEST[$key];
                }
            }
        }
    }

    protected function getLastUsedFilter()
    {
        $this->lastUsedFilter = $this->jsonStorage->getData('lastUsedFilter', $this->registry);
        
        if (!empty($this->lastUsedFilter))
        {
            $this->lastUsedFilter['options']['lastUsed'] = empty($this->lastUsedFilter['name']) ? TRUE : $this->lastUsedFilter['name'];
            $this->redirectRoute = $this->cleanUrlQuery($this->redirectRoute, '', $this->lastUsedFilter['options']);
            return true;
        }
        
        return false;
    }
    
    protected function saveLastUsedFilter()
    {
        $data = array('name' => Yii::app()->getRequest()->getParam($this->saveFilterVar));
        $data['options'] = $this->formatOptions();
        $this->jsonStorage->setData('lastUsedFilter', $data, $this->registry);
    }
    
    protected function removeLastUsedFilter()
    {
        $this->jsonStorage->removeData('lastUsedFilter', $this->registry);
        $this->redirectRoute = $this->cleanUrlQuery($this->redirectRoute, 'clearFilter');
    }

	/**
	 *### .checkRequestRemovalFilter()
	 *
	 * Checks whether there has been send the command to remove a filter from the registry and redirects to
	 * specified route
	 */
	protected function checkRequestRemovalFilter()
	{
        $key = Yii::app()->getRequest()->getParam($this->removeFilterVar);
        
		if ($key)
		{
			if ($this->jsonStorage->removeData($key, $this->registry))
            {
                $this->redirectRoute = $this->cleanUrlQuery($this->redirectRoute, $this->removeFilterVar, array());
                
				Yii::app()->controller->redirect($this->redirectRoute);
            }
		}
	}

	/**
	 *### .checkRequestFilters()
	 *
	 * Checkes whether there has been send the command to save a filter to the registry and redirects to
	 * specified route
	 *
	 * @return bool
	 */
	protected function checkRequestFilters()
	{
        $filterName = Yii::app()->getRequest()->getParam($this->saveFilterVar);
        
		if ($filterName)
		{
			if (!count($this->filteredBy) && !count($this->addlFilters))
				return false;
			$key = $this->generateRegistryItemKey();

			if ($this->jsonStorage->getData($key, $this->registry))
				return false;

			$data = array('name' => $filterName);
            
            $data['options'] = $this->formatOptions();
            
			$this->jsonStorage->setData($key, $data, $this->registry);
            
            $this->redirectRoute = $this->cleanUrlQuery($this->redirectRoute, $this->saveFilterVar);
            
			Yii::app()->controller->redirect($this->redirectRoute);
		}
	}
    
    protected function formatOptions()
    {
        if (!empty($this->addlFilters))
        {
            return CMap::mergeArray(
                array(get_class($this->model) => $this->filteredBy),
                $this->addlFilters
            );
        }
        else
        {
            return array(get_class($this->model) => $this->filteredBy);
        }
    }

    public static function cleanUrlQuery($dirtyUrl, $toClean=NULL, $customQuery=NULL)
    {
        $parsedUrl = parse_url($dirtyUrl);
        
        $queryParams = array();
        if (empty($customQuery))
            parse_str($parsedUrl['query'], $queryParams);
        else
            $queryParams = $customQuery;
        
        if (!empty($queryParams))
        {
            if (is_string($toClean))
            {
                if (isset($queryParams[$toClean]))
                {
                    unset($queryParams[$toClean]);
                }
            }
            elseif (is_array($toClean))
            {
                foreach ($toClean as $param)
                {
                    if (isset($queryParams[$param]))
                    {
                        unset($queryParams[$param]);
                    }
                }
            }

            $parsedUrl['query'] = http_build_query($queryParams);
        }
        
        
        $cleanedUrl = '';
        
        foreach (array('scheme', 'host', 'port', 'user', 'pass', 'path', 'query', 'fragment') as $component)
        {
            if (isset($parsedUrl[$component]))
            {
                switch ($component)
                {
                    case 'scheme':
                        $cleanedUrl .= $parsedUrl['scheme'].'://';
                        break;

                    case 'host':
                    case 'path';
                        $cleanedUrl .= $parsedUrl[$component];
                        break;

                    case 'query':
                        $cleanedUrl .= '?'.$parsedUrl['query'];
                        break;

                    case 'fragment':
                        $cleanedUrl .= '#'.$parsedUrl['fragment'];
                }
            }
        }
        
        return $cleanedUrl;
    }
    
    protected function processPersistVars($toClean=array())
    {
        if (!empty($this->persistVars))
        {
            foreach ($this->persistVars as $var)
            {
                if (isset($_REQUEST[$var]))
                    $this->persistQuery[$var] = $_REQUEST[$var];
            }
        }
    }

	/**
	 *### .run()
	 *
	 * Widget's run method
	 */
	public function run()
	{
		$registryKey = $this->generateRegistryItemKey();

		if ($this->grid->ajaxUpdate || (!count($this->filteredBy) && !count($this->addlFilters) && $this->jsonStorage->getLength($this->registry) <= 1))
			return;

		echo "<tr>\n";
		$cols = count($this->grid->columns);
		echo "<td colspan='{$cols}'>\n";
		echo "<div id='{$this->getId()}'>\n";
        
//		if (count($this->filteredBy) or count($this->addlFilters))
//			echo '<p><span class="label label-success">Filtered by</span> ' . $this->displayExtendedFilterValues(CMap::mergeArray($this->filteredBy, $this->addlFilters)) . '</p>';

        echo '<div>';
            $this->displaySavedFilters($registryKey);
            $this->displaySaveButton($registryKey);
        echo '</div>';
        
		echo "</div>\n";
		echo "</td>\n";
		echo "</tr>\n";
	}

	/**
	 *### .registerClientScript()
	 *
	 * Registers the required
	 */
	public function registerClientScript()
	{
        $this->redirectRoute = $this->cleanUrlQuery($this->redirectRoute, array(), CMap::mergeArray($this->persistQuery, array('clearFilter' => true)));
		$url = CHtml::normalizeUrl($this->redirectRoute);
        
		Yii::app()->clientScript->registerScript(__CLASS__ . '#extended-filter' . $this->grid->id, <<<EOD
		$(document).on('click', '#{$this->grid->id} .btn-extended-filter-save', function(e){
			e.preventDefault();
			bootbox.prompt("Enter name or description of filter...", "Cancel", "Save", function(result){
				if ($.trim(result).length > 0)
				{
					$('#{$this->grid->id}').yiiGridView('update',{data:{{$this->saveFilterVar}:result}});
				}
			});
		});
        
		$(document).on('click', '#{$this->grid->id} .btn-extended-filter-apply', function(e) {
			e.preventDefault();
			var option = $('#{$this->getId()} select.select-extended-filter option:selected');
			if (!option.length || !option.data('filter'))
			{
				return false;
			}
			var data ={data:option.data('filter')};
			if (option.val()==-1)
			{
                clearBtn = $('#{$this->grid->id} .btn-extended-filter-clear');
                data.url = clearBtn.length > 0 ? clearBtn.attr('href') : $.trim("{$url}");
                //data.url = $.trim("{$url}");
                //location.href = "{$url}".split("?")[0]+"?clearFilter=1";
			}
            $('#{$this->grid->id}').yiiGridView('update',data);
		});

		$(document).on('click', '#{$this->grid->id} .btn-extended-filter-delete', function(e) {
			e.preventDefault();
			var option = $('#{$this->grid->id} select.select-extended-filter option:selected');
			if (!option.length || !option.data('key') || option.val()==-1)
			{
				return false;
			}
			bootbox.confirm('<h3>Delete "'+option.text()+'" filter?</h3>', function(confirmed){
				if (confirmed)
				{
					$('#{$this->grid->id}').yiiGridView('update',{data:{{$this->removeFilterVar}:option.data('key')}});
				}
			});
		});
        
        $('#{$this->grid->id} .btn-extended-filter-apply').tooltip({
            "title": "Apply filter",
            "html": true
        });
        
        $('#{$this->grid->id} .btn-extended-filter-delete').tooltip({
            "title": "Delete filter",
            "html": true
        });
EOD
		);
	}

	/**
	 *### .displaySaveButton()
	 *
	 * Displays the save filter button
	 *
	 * @param string $registryKey
	 * @return bool
	 */
	protected function displaySaveButton($registryKey)
	{
		if (null == $registryKey || $this->jsonStorage->getData($registryKey, $this->registry))
			return false;
        
        $clearFilterLink = CMap::mergeArray(array(''), $this->persistQuery);
        $clearFilterLink['clearFilter'] = true;
        
        echo CHtml::link('<span>Save filter</span>', '#', array('class'=>'btn btn-small btn-success btn-extended-filter-save', 'style'=>'margin-bottom:9px'));
        echo '&nbsp;';
        echo CHtml::link('<span>Clear filter</span>', $clearFilterLink, array('class'=>'btn btn-small btn-warning btn-extended-filter-clear', 'style'=>'margin-bottom:9px'));
    }

	/**
	 *### .displaySavedFilters()
	 *
	 * displays the saved filters as a dropdown list
	 *
	 * @param string $registryKey
	 */
	protected function displaySavedFilters($registryKey)
	{
		if ($this->jsonStorage->getLength($this->registry) > 1)
		{
			$registry = $this->jsonStorage->getRegistry($this->registry);

            echo '<select class="select-extended-filter">';
            echo '<option value="-1" data-filter="'.htmlspecialchars(CJSON::encode(CMap::mergeArray($this->persistQuery, array('clearFilter' => true)))).'" '.(!$registryKey ? 'selected' : '').'>'.(!$registryKey ? 'No filters' : 'Clear filters').'</option>';
            
            unset($registry['lastUsedFilter']);
            
            foreach ($registry as $key=>$filter)
            {
                echo CHtml::openTag('option', array(
                    'data-filter'=>CJSON::encode($filter['options']),
                    'data-key'=>$key,
                    'selected'=>($key==$registryKey?'selected':null)
                ));
                echo $filter['name'];
                echo '</option>';
            }
            echo '</select>&nbsp;';

			echo CHtml::link('<i class="icon-ok icon-white"></i>', '#', array('class'=>'btn btn-primary btn-extended-filter-apply', 'style'=>'margin-bottom:9px'));
			echo '&nbsp;';
			echo CHtml::link('<i class="icon-trash"></i>', '#', array('class'=>'btn btn-warning btn-extended-filter-delete', 'style'=>'margin-bottom:9px'));
            echo '&nbsp;';
		}
	}

	/**
	 *### .generateRegistryItemKey()
	 *
	 * Generates a registry item key with the filtered attributes + the grid id
	 *
	 * @return null|string
	 */
	protected function generateRegistryItemKey()
	{
		if (!count($this->filteredBy) and !count($this->addlFilters))
			return null;
		return md5($this->grid->id . CJSON::encode($this->formatOptions()));
	}

	/**
	 *### .displayExtendedFilterValues()
	 *
	 * Displays the filtered options
	 *
	 * @param array $filteredBy
	 * @return string
	 */
	protected function displayExtendedFilterValues($filteredBy)
	{
		$values = array();
		foreach ($filteredBy as $key => $value)
			$values[] = '<span class="label label-info">' . $key . '</span> ' . $value;
		return implode(', ', $values);
	}
}
