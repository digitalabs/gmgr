<?php /* @var $this Controller */

//$user = User::model()->findByPk(Yii::app()->session['user_id']);
if(empty($user)) $username = '';
else $username = $user->first_name;

if(Yii::app()->session['userMode'] == 'pipeline')
	$mode = 'Pipeline';
else $mode = 'Cross-cutting';

$appTheme = Yii::app()->theme;
$appController = Yii::app()->getController();
$controllerUniqueId = $appController->getUniqueId();
$actionUniqueId = Yii::app()->controller->action->id;
$appUser = Yii::app()->user;
$userIsGuest = $appUser->isGuest;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
       <!-- blueprint CSS framework -->
            <link rel="stylesheet" type="text/css" href="<?php echo $appTheme->baseUrl; ?>/css/screen.css" media="screen, projection" />
            <link rel="stylesheet" type="text/css" href="<?php echo $appTheme->baseUrl; ?>/css/print.css" media="print" />
            <!--[if lt IE 8]>
            <link rel="stylesheet" type="text/css" href="<?php echo $appTheme->baseUrl; ?>/css/ie.css" media="screen, projection" />
            <![endif]-->
            <link rel="shortcut icon" href="<?php echo $appTheme->baseUrl; ?>/img/icon3.png" type="image/x-icon" />
            <link rel="stylesheet" type="text/css" href="<?php echo $appTheme->baseUrl; ?>/css/main.css" />
            <link rel="stylesheet" type="text/css" href="<?php echo $appTheme->baseUrl; ?>/css/form.css" />
            <link rel="stylesheet" type="text/css" href="<?php echo $appTheme->baseUrl; ?>/css/custom.css" />
            <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>
    <body>

        <div class="container" id="page">
            <div id="mainmenu"><div class="span6"><font color="#C0C0C0"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Genealogy Manager</b></font></div>
            <?php
                $this->widget('zii.widgets.CMenu', array(
				'encodeLabel'=>false,
				'items' => array(
			//array(
				//'htmlOptions'=>array('class'=>'pull-left'),
				//'items'=>array(
					//array(
						//'label' => 'Genealogy Manager',
						//'url' => array('/login/setMode','id'=>'1'),
					//),
				//),
			//),
			array('label' => 'Home', 'icon'=>'home', 'url' => array('/site/index')),
			//array("label" => "What's New", 'url' => array('/site/login')),
			/*array('label' => 'About', 'url' => array('/site/page', 'view' => 'about')),*/
			//array('label' => 'Contact', 'url' => array('/site/contact')),
			//array('label' => 'Set preferences', 'url' => array('/login/entryPoint'), 'visible' => Yii::app()->session['entrypoint_isset'] == 0 && !$userIsGuest),
			array('label' => 'Login', 'url' => array('/site/login'), 'visible' => $userIsGuest),
			array('visible' => !$userIsGuest && Yii::app()->session['entrypoint_isset'] == 1,
				'label' => 'Mode: ' .$mode. '&nbsp;<div class="carets"></div>',
				'url' => '#',
				'submenuOptions' => array( 'class' => 'drop' ),
				'items' => array(
					array(
						'label' => 'Pipeline',
						'url' => array('/login/setMode','id'=>'1'),
					),
					array(
						'label' => 'Cross-cutting',
						'url' => array('/login/setMode','id'=>'2')
					),
				),
				'itemOptions' => array( 'class' => 'dropdown' ),
				'linkOptions' => array( 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown' ),
			),
			//array('visible' => !$userIsGuest, 'label' => 'Welcome back, '. $username .'!'),
			array('visible' => !$userIsGuest,
				'label' => ' <i class="icon-user icon-white"></i> <div class="carets"></div>',
				'url' => '#',
				'submenuOptions' => array( 'class' => 'drop' ),
				'items' => array(
					//array(
					//	'label' => 'View profile',
					//	'url' => array( '#' ),
					//),
					array(
						'label' => 'My saved lists',
						'url' => array('/userList/userList/'),
						'visible' => Yii::app()->session['entrypoint_isset'] == 1,
					),
					array('label' => 'Logout ('.Yii::app()->user->name.')', 'url' => array('/site/logout'), 'visible' => !$userIsGuest)
				),
				'itemOptions' => array( 'class' => 'dropdown' ),
				'linkOptions' => array( 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown' ),
			),
		),
	)); ?>
            </div>

<?php if (!$userIsGuest) { ?>
                <!--<div id="logo-pipeline" style="padding:40px 50px 10px 30px;" >
				<!--<h2 style="font-family: 'Pontano Sans', sans-serif; margin-top: 10px;"><font color="#696969">Genealogy Manager</font></h2>-->
                    <?php
	$image = CHtml::image(Yii::app()->baseUrl . '/images/logoHeader.png', 'International Rice Research Institute');
	//echo CHtml::link($image, array('/site/importer'));
	?><span class="right">
                        <?php
	if(Yii::app()->session['entrypoint_isset'] == 1)
	{
		if(Yii::app()->session['userMode'] == 'pipeline')
		{

			if(!empty(Yii::app()->session['tvp_isset'])){
				$tvp = Tvp::model()->findByPk(Yii::app()->session['tvp_isset']);
				$tvpLabel = $tvp->tvp_name;
			}else {$tvpLabel = 'Irrigated South-East Asia Improved';}
			$tvpModels = Tvp::model()->findAll();
			foreach($tvpModels as $model) {
				$tvpLabels[] = array(
					'label'=>$model->tvp_name,
					'url' => Yii::app()->createAbsoluteUrl('/login/setTvpLabel',array('id' => $model->id))
				);
			}
			echo "<b style='font-size:11px;'>Target Variety Program: </b>";
			$this->widget('bootstrap.widgets.TbButtonGroup', array(
					'type' => '', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
					'buttons' => array(
						array('label' => $tvpLabel,
							'items' =>$tvpLabels
							//                                            array(
							//                                                array('label' => 'Irrigated Southeast Asia', 'url' => Yii::app()->createAbsoluteUrl('study/study'), ''),
							//                                                array('label' => 'Rainfed South-East Asia', 'url' => Yii::app()->createAbsoluteUrl('site/index')),
							//                                            /* '---',
							//                                              array('label'=>'Irrigated Southeast Asia', 'url'=>'#'), */
							//                                            )
						),
					),
				));
		}
		else{
			echo "<b>Activity: </b>";
			$activityLabel = (Yii::app()->session['activity_isset'] == 'hybridization') ? 'Hybridization' : 'RGA';
			$this->widget('bootstrap.widgets.TbButtonGroup', array(
					'type' => '', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
					'buttons' => array(
						array('label' => $activityLabel, 'items' => array(
								array('label' => 'Hybridization', 'url' => Yii::app()->createAbsoluteUrl('/login/setActivityLabel',array('id' => 0))),
								array('label' => 'RGA', 'url'=>Yii::app()->createAbsoluteUrl('/login/setActivityLabel',array('id' => 1)))
								/* '---',
                                              array('label'=>'Irrigated Southeast Asia', 'url'=>'#'), */
							)),
					),
				));
		}
	}
?>
                    <?php } ?>
                </span>
           <!-- </div>-->
            <!-- mainmenu -->
            
            <?php if (!$userIsGuest) { ?>
            <?php
	// if(Yii::app()->session['entrypoint_isset'] == 0)
	// {
		// $items = array(array(
				// 'label' => ' ',
			// ),);
	// }
	// else
	// {
		//if(Yii::app()->session['userMode'] == 'pipeline')
		//{
			$items = array(

				array(
					'label' => 'Pipeline Manager',
					'url' => array('/pipelineManager'),
					'active' => (strcasecmp($controllerUniqueId, 'pipelineManager/default') === 0) ? true : false,
				),
				array(
					'label' => 'Study Module',
					'url' => array('/study/study'),
					'active' => (strcasecmp(Yii::app()->controller->id, 'study') === 0 || strcasecmp($controllerUniqueId, 'study/crossing') === 0
						|| strcasecmp($controllerUniqueId, 'study/rga') === 0 || strcasecmp($controllerUniqueId, 'study/f1') === 0) ? true : false,
				),
				array(
					'label' => 'Data Collection',
					'url' => array('/dataCollection'),
					'active' => (strcasecmp($controllerUniqueId, 'dataCollection/default') === 0) ? true : false,
				),
				array(
					'label' => 'Data Analysis',
					'url' => array('/dataAnalysis'),
					'active' => (strcasecmp($controllerUniqueId, 'dataAnalysis/default') === 0) ? true : false,
				),
				array(
					'label' => 'System Modules',
					'url' => array('/systemTools'),
					'active' => (strcasecmp($controllerUniqueId, 'systemTools/default') === 0) ? true : false,
				),

				array(
					'label' => 'Tools &emsp;&emsp;',
					'url' => array('/adminTools'),
					'active' => (strcasecmp($controllerUniqueId, 'adminTools/default') === 0) ? true : false,
					'items' => array(
						array('label' => 'Germplasm (Fixed Lines)', 'url' => array('/fixedLineManager/fixedLine'), 'active' => (strcasecmp($controllerUniqueId, 'adminTools/default') === 0) ? true : false),
						array('label' => 'Seed Stocks', 'url' => array('/seedStock/fixedLine'), 'active' => (strcasecmp($controllerUniqueId, 'adminTools/default') === 0) ? true : false, 'visible'),
						array('label' => 'Traits and Variables', 'url' => array('/variable/variable'), 'visible'=>$appUser->checkAccess('Data provider'), 'active' => (strcasecmp($controllerUniqueId, 'adminTools/default') === 0) ? true : false),
						array('label' => 'Variable Sets', 'url' => array('/variable/variableSet'), 'visible'=>$appUser->checkAccess('Data provider'), 'active' => (strcasecmp($controllerUniqueId, 'adminTools/default') === 0) ? true : false),
						array('label' => 'Study Templates', 'url' => array('/template/studyTypeTemplate'), 'active' => (strcasecmp($controllerUniqueId, 'adminTools/default') === 0) ? true : false),
						array('label' => 'Country', 'url' => array('/locationManager/country'), 'active' => (strcasecmp($controllerUniqueId, 'adminTools/default') === 0) ? true : false),
						array('label' => 'Trial Test Location', 'url' => array('/locationManager/trialTestLocation'), 'active' => (strcasecmp($controllerUniqueId, 'adminTools/default') === 0) ? true : false),
						array('label' => 'Audit Trail', 'url' => array('/audit/auditTrail'), 'visible'=>$appUser->checkAccess('Data provider'), 'active' => (strcasecmp($controllerUniqueId, 'adminTools/default') === 0) ? true : false),
						array('label' => ' ', 'url' => array('/toolTip/tblTooltip'), 'active' => (strcasecmp($controllerUniqueId, 'adminTools/default') === 0) ? true : false),
						array('label' => 'Tooltips', 'url' => array('/toolTip/tblTooltip'), 'visible'=>$appUser->checkAccess('Data provider'), 'active' => (strcasecmp($controllerUniqueId, 'adminTools/default') === 0) ? true : false),
						array('label' => 'Users and Roles', 'url' => array('/auth/user'), 'visible'=>$appUser->checkAccess('Admin'), 'active' => (strcasecmp($controllerUniqueId, 'adminTools/default') === 0) ? true : false),
					),



				),

			);

		//}
		//else
		//{
			$items = array(
				// RGA Tray Inventory Module
				array(
					'label' => 'Pedigree Import',
					'url' => array('/site/importer'), //Changed landing page into Minuro Tray
					'active' => (strcasecmp($controllerUniqueId, 'rgaContainerInventory/rgaContainerInventory') === 0) ? true : false,
				),
				// Lists all HB entries for centralized data entry
				array(
					'label' => 'Pedigree Editor',
					'url' => array('/site/editor'),
					'active' => (strcasecmp($actionUniqueId, 'showHBEntries') === 0 ||
                                                     strcasecmp($actionUniqueId, 'showSingleCross') === 0 ||
                                                     strcasecmp($actionUniqueId, 'showBackCross') === 0 ||
                                                     strcasecmp($actionUniqueId, 'showMultiCross') === 0) ? true : false,
				),

			);
		//}
	//}

?>
                <div id="mainmenu2">
                    <?php
	$this->widget('zii.widgets.CMenu', array(
			'activeCssClass' => 'active',
			'encodeLabel' => false,
			'activateParents' => true,
			'items' => $items,
		));

?>

            </div><!-- mainmenu2 -->
			
            <div id="mainmenu3">
                <?php
	$this->widget('zii.widgets.CMenu', array(
			'items' => $this->menu
		));
?>
            </div><!-- mainmenu3 -->
            <?php } ?>
                <?php echo $content; ?>

            <div class="clear"><br></div>
            <div id="footer">
                <?php
//echo "Git Commit ID: " . Yii::app()->git->getLastCommitId();
//echo " / ";
//echo "Last Commit Time: " . Yii::app()->git->getLastCommitDate();
//echo " / ";
echo Yii::powered();
?>
            </div><!-- footer -->
        </div><!-- page -->
    </body>
</html>
