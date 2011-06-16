<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="language" content="en" />

  <!-- blueprint CSS framework -->
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/minimal/screen.css" media="screen, projection" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
  <!--[if lt IE 8]>
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
  <![endif]-->

  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/minimal/main.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/minimal/form.css" />

  <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

  <div id="mainmenu">
    
    <?php echo CHtml::ajaxLink('<span class="mainButtonText">Login</span>',CHtml::normalizeUrl(array('site/ajaxLogin')),array('type'=>'post',
            'dataType'=>'json',
            'update' => '#dialogLogin'),array('id'=>'send-link-'.uniqid(),
                                       'onclick'=>"$('#dialogLogin').dialog('open');",
                                       'class'=>'mainLoginButton_bg',
                                       'style'=>!Yii::app()->user->isGuest?'visibility:hidden':''
                                       
                                             ));?>
    
   
    <?php echo CHtml::Link('<span style=margin-right:25px; class="mainButtonText">Logout</span>',CHtml::normalizeUrl(array('site/logout')),array('class'=>'link_button',
                                       'style'=>Yii::app()->user->isGuest?
                                       'visibility:hidden;':'margin-left:-100px;',
                                       'class'=>Yii::app()->user->isGuest?'mainLoginButton_bg':'mainLogoutButton_bg'))?>
    
    <?php echo CHtml::Link('<span style=margin-right:6px; class="mainButtonText">MyBookmarks</span>',CHtml::normalizeUrl(array('/bookmark')),array(
                                       'class'=>!Yii::app()->user->isGuest?'mainButtonLogged'
                                      :'mainButton'
                                             ));?>
    
    
  </div>  
    <?php
  
$this->beginWidget('zii.widgets.jui.CJuiDialog', array( // the dialog
    'id'=>'dialogLogin',
    'options'=>array(
        'title'=>'Login',
        'autoOpen'=>false,
        'modal'=>true,
        'width'=>550,
        'height'=>470,
      //'close' => array('js:function(){$(this).dialog("destroy")}'),
      //'buttons' => array('hallo','js:function(){$(this).dialog("close")}'),
    ),
));?>
<div id="divForLoginForm"></div>
 
<?php $this->endWidget();?>
  <!--
  <div id="header">
    <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
  </div><!-- header -->
    
  
  
  <!--<?php if(isset($this->breadcrumbs)):?>
    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
      'links'=>$this->breadcrumbs,
    )); ?>
  <?php endif?>-->
  

 

  <?php echo $content; ?>

  <div id="footer">
    Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
    All Rights Reserved.<br/>
    <?php echo Yii::powered(); ?>
  </div><!-- footer -->

</div><!-- page -->

</body>
</html>