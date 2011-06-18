<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<div class='baseLogin'>
    <a id='baseLoginButton_bg'><span class='baseLoginButtonText'><img src='themes/minimal/images/logo1.png' alt='logo' /></span></a>

<!--<div class='baseContent'>-->
  <div id='withText'>with</div>
  <div id='divForAjaxLoginForm'></div>
<!--</div>-->
</div>


  
  
  
  
  
  
  
  
  
  <script type="text/javascript">
  
    $(document).ready(function(){
      
    $(".baseLoginButtonText").toggle(function(){
        $("#baseLoginButton_bg").animate({
            marginLeft:'-=38%'
        },500,'swing',function(){
            $("#divForAjaxLoginForm").css({borderLeft: '#ddd 2px solid'})
            $("#divForAjaxLoginForm").load("index.php?r=site/ajaxlogin");
        });
        
    },
    function(){
        $("#baseLoginButton_bg").animate({
            marginLeft: '+=38%'
        },500,'swing');
        $("#divForAjaxLoginForm").css({borderLeft: ''})
        $("#divForAjaxLoginForm").empty();
    });
      
      
 



        
  });
  </script>
