<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap');
return array(
    'theme' => 'bootstrap',
    'modules' => array(
        'gii' => array(
            'generatorPaths' => array(
                'bootstrap.gii',
            ),
        ),
    ),
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Genealogy Manager',
    'defaultController' => 'login',
    // preloading 'log' component
    'preload' => array('log', 'bootstrap',),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.extensions.*',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
            'generatorPaths' => array('ext.bootstrap.gii'),
        ),
    ),
    // application components
    'components' => array(
        'bootstrap' => array('class' => 'ext.bootstrap.components.Bootstrap',
            'responsiveCss' => true,
        ),
        /*'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,


           // 'class'=>'WebUser',
        ),
        // uncomment the following to enable URLs in path-format
        /*
          'urlManager'=>array(
          'urlFormat'=>'path',
          'rules'=>array(
          '<controller:\w+>/<id:\d+>'=>'<controller>/view',
          '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
          '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
          ),
          ),
         */
/*
        // uncomment the following to use a MySQL database
         'db'=>array(
          'connectionString' => 'mysql:host=localhost;dbname=central6',
          'emulatePrepare' => true,
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
          'enableProfiling' => true,
           'enableParamLogging'=>true,   
          ),
  */        
          'db'=>array(
          'class'=>'CDbConnection',
          'connectionString' => 'mysql:host=127.0.0.1;port=3306;dbname=iris_mysiam_20121002',
          'emulatePrepare' => true,
          'username' => 'datasourceuser',
          'password' => 'ici$rule$',
          'charset' => 'utf8',
          'enableProfiling' => true,
          'enableParamLogging'=>true,
          ),
         
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'defaultPageSize' => 10,
        'adminEmail' => 'webmaster@example.com',
    ),
);
