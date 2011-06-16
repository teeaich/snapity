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


  <div id="mainmenu"></div>
 
  <!-- dialog coding for login begins here-->
  
  <?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array( // the dialog
    'id'=>'dialogLogin',
    'options'=>array(
        'title'=>'Login',
        'autoOpen'=>false,
        'modal'=>true,
        'width'=>550,
        'height'=>470,
        ),
    ));
  ?>
  <div id="divForLoginForm"></div>
  <?php $this->endWidget();?> 
  <!-- dialog coding for login ends here-->

  <!-- at this point the content starts from the chosen layout in folder /layouts which itself 
  is defined in a controller-->
  <?php echo $content; ?>
  
  <!-- ajaxlink to start the dialog widget with callback function-->
  <!--<div class="baseLogin">
    <?php echo CHtml::ajaxLink('<span class="link_button">Login</span>',
                     CHtml::normalizeUrl(array('site/ajaxLogin')),array(
                       'type'=>'post',
                       'dataType'=>'json',
                       'update' => '#dialogLogin'),array(
                           'id'=>'send-link-'.uniqid(),
                         //'onclick'=>"$('#dialogLogin').dialog('open');",
                           'class'=>'baseLoginButton_bg',        
                           ));
     ?>-->
    
  <!--</div><!-- end of ajaxlink to start dialog-->
  
  <!--<div id="divForLoginContent"></div>-->
  <!-- begin of footer-->
  <div id="footer">
    Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
    All Rights Reserved.<br/>
    <?php echo Yii::powered(); ?>
  </div><!-- end of footer -->

</div><!-- end of class=container and id=page -->
         

</body>
</html>