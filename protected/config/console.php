<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Console Application',
    // preloading 'log' component
    'preload' => array('log'),
    // application components
    'components' => array(
         'db'=>array(
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ), 
        // uncomment the following to use a MySQL database
        /* 'db'=>array(
          'class'=>'CDbConnection',
          'connectionString' => 'mysql:host=127.0.0.1;port=3306;dbname=iris_mysiam_20121002',
          'emulatePrepare' => true,
          'username' => 'datasourceuser',
          'password' => 'ici$rule$',
          'charset' => 'utf8',

          ), */
        /* 'db'=>array(
          'connectionString' => 'mysql:host=localhost;dbname=central6',
          'emulatePrepare' => true,
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
          ), */
        /*'db' => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=127.0.0.1;port=3306;dbname=iris_mysiam_20121002',
            'emulatePrepare' => true,
            'username' => 'datasourceuser',
            'password' => 'ici$rule$',
            'charset' => 'utf8',
        ),*/
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),
);
