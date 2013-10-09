<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// application components
	'components'=>array(
		/*'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=central',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
<<<<<<< HEAD
			
		),
		Yii::app()->db->close();
		    /*
=======
		),*/
		
>>>>>>> 1ac55b28b876a7e55874149580ff09904c3a2887
			'db'=>array(
		    'class'=>'CDbConnection',
			'connectionString' => 'mysql:host=127.0.0.1;port=5528;dbname=iris_myisam_20121002',
			'emulatePrepare' => true,
			'username' => 'phenibquser',
			'password' => 'phenibqpass',
			'charset' => 'utf8',
<<<<<<< HEAD
		),*/
=======
		),
>>>>>>> 1ac55b28b876a7e55874149580ff09904c3a2887
		
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);
