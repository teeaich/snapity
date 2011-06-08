<?php
  
 



// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
  'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
  'name'=>'Startseite',
  'theme'=>'minimal',
  

  // preloading 'log' component
  'preload'=>array('log'),

  // autoloading model and component classes
  'import'=>array(
    'application.models.*',
    'application.components.*',
    'ext.eoauth.*',
    'ext.eoauth.lib.*',
    //'ext.runactions.components.*',
    
    
  ),

  'modules'=>array(
    // uncomment the following to enable the Gii tool
    
    'gii'=>array(
      'class'=>'system.gii.GiiModule',
      'password'=>'Betze',
       // If removed, Gii defaults to localhost only. Edit carefully to taste.
      'ipFilters'=>array('127.0.0.1','::1','93.210.182.134','79.225.13.246','*.*.*.*'),
    ),
    
  ),
  

  // application components
  'components'=>array(
    'user'=>array(
      // enable cookie-based authentication
      'allowAutoLogin'=>true,
    ),
    // enables theme based JQueryUI's
     'widgetFactory' => array(
            'widgets' => array(
                'CListView'=>array(
                  'cssFile' => dirname(Yii::app()->getBasePath()).'/start/css/minimal/listview/styles.css',
                 ),
             ),
       
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
    
    'db'=>array(
      'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
    ),
    */
    // uncomment the following to use a MySQL database
    
    // for auzuli.org
    /*
    'db'=>array(
      'connectionString' => 'mysql:host=mysql.azuli.org;dbname=u867754927_k1766',
      'emulatePrepare' => true,
      'username' => 'u867754927_k1766',
      'password' => 'Betzepower01',
      'charset' => 'utf8',
      'tablePrefix' => 'tbl_',
    ),
    */
    // for kodingen
    'db'=>array(
      'connectionString' => 'mysql:host=localhost;dbname=k17663_3',
      'emulatePrepare' => true,
      'username' => 'k17663_3',
      'password' => 'Betzepower01',
      'charset' => 'utf8',
      'tablePrefix' => 'tbl_',
    ),
    
    'errorHandler'=>array(
      // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
    'log'=>array(
      'class'=>'CLogRouter',
      'routes'=>array(
        array(
          'class'=>'CFileLogRoute',
          'levels'=>'trace,info,error, warning',
        ),
        // uncomment the following to show log messages on web pages
        
        array(
          'class'=>'CWebLogRoute',
          'levels'=> 'trace,info,error,warning',
        ),
        
      ),
    ),
    
    'loid' => array(
               //alias to dir, where you unpacked extension
    'class' => 'application.extensions.lightopenid.loid',
  ),
    
     
  ),

  // application-level parameters that can be accessed
  // using Yii::app()->params['paramName']
  'params'=>array(
    // this is used in contact page
    'adminEmail'=>'webmaster@example.com',
  ),
  
  
);