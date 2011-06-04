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
<!-- Für die Transparenz des zweiten backgrounds, 
  leider nicht so einfach machbar da background fixed sein muss. doof
  <div id="page_opacity">
  </div>
-->
<div class="container" id="page">
  <!-- Just a test to demostrate the controller is doing the right work when contacted by a normal ajaxlink-->
  <!--<?php echo CHtml::ajaxLink('TestLoginAjaxLink',CHtml::normalizeUrl(array('site/ajaxLogin')),array('type'=>'post',
            'dataType'=>'json',
            'update'=>'#dialogLogin'),array('id'=>'send-link-'.uniqid(),
                                       'onclick'=>"$('#dialogLogin').dialog('open');"));?>
  -->

  
  <div id="mainmenu">
    <?php $this->widget('ext.AjaxMenu',array(
      'items'=>array(
        array(
              'label'=>'Login', 
              'url'=>array(
                           '/site/ajaxLogin'),
              'linkOptions'=>array(
                                   'id'=>'send-link-'.uniqid(),
                                   'class'=>'login_button',
                                   'onclick'=>"$('#dialogLogin').dialog('open');"),
              'ajax'=>array(
                           'dataType'=>'json',
                           'update'=>'#dialogLogin'),
              'visible'=>Yii::app()->user->isGuest),
        array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest,'linkOptions' => array( 
          'class'=>'login_button')),
        array('label'=>'MyBookmarks', 'url'=>array('/bookmark'),'linkOptions'=> array(
          'class'=>'main_buttons')),
        /*array('label'=>'About', 'url'=>array('/site/page','view'=>'about'),'linkOptions'=> array(
          'class'=>'main_buttons')),
        
        
        array('label'=>'Contact', 'url'=>array('/site/contact'),'linkOptions'=> array(
        'class'=>'main_buttons')),*/
      ),
    )); 
       

      ?>
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
  
  <div id="header">
    <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
  </div><!-- header -->
    
  </div><!-- mainmenu -->
  
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