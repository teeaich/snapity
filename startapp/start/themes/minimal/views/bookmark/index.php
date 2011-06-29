<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
<script type="text/javascript" src="/startapp/start/themes/minimal/js/corner.js"></script>
<script src="/startapp/start/themes/minimal/js/jquery.tools.min.js"></script>
<script src="/startapp/start/themes/minimal/js/jquery.mousewheel.js"></script>
<script src="/startapp/start/themes/minimal/js/axzoomer1.4.js"></script>

<?php $this->widget('zii.widgets.CListView', array(
  'dataProvider'=>$dataProvider,
  'itemView'=>'_view',
  'template'=> '{items}{pager}'
  //nicht mehr notwenig da imagelink in bookmark controller nun definiert ist
  //'viewData'=> array (
  //'imageLink' => Config::getConfigValue('image_provider_link'),
  //                 ),
)); 
  
?>

<?php echo CHtml::ajaxLink('Create Bookmark',CHtml::normalizeUrl(array('bookmark/ajaxcreate')),array('type'=>'post',
            'dataType'=>'json',
            'success'=>"js:function(data) 
                             {
                               if (data.status == 'failure') 
                                   {
                                      $('#dialogBookmark').html(data.div);
                                    //alert ('mein Titel','status failure');
                                   }
                               if (data.status == 'success')
                                   {
                                      $('#dialogBookmark').html(data.div);                                             
                                    //alert ('Titel','else');
                                      $('#dialogBookmark').dialog('close');                                                                                                  
                                   }                                                             
                                                                                             
                               
                             }"),array('id'=>'send-link-'.uniqid(),
                                       'class'=>'createBookmarkButton',
                                       'onclick'=>"$('#dialogBookmark').dialog('open');"))
                                                                                                  
                              ?>

 
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array( // the dialog
    'id'=>'dialogBookmark',
    'options'=>array(
        'title'=>'Create Bookmark',
        'autoOpen'=>false,
        'modal'=>true,
        'width'=>550,
        'height'=>300,
        
    ),
));?>
<div id="divForForm"></div>
 
<?php $this->endWidget();?>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array( // the dialog
    'id'=>'dialogEditImage',
    'options'=>array(
        'title'=>'Crop the Image',
        'autoOpen'=>false,
        'modal'=>true,
        'width'=>1200,
        'height'=>850,
        
    ),
));?>
<div id="divForEditImage"></div>
 
<?php $this->endWidget();?>


<script type="text/javascript">
$.ajaxSetup ({  
    cache: false  
});  
    
// global var js
var ajax_load = "<img src='themes/minimal/images/load.gif' alt='loading...' />"; 

    
var animateIn = function animateIn() {
    //checks if a headerbox is still open from a previous imagebox
    $('.tooltip').each(function() {
        if ($(this).css("height") != '40px') {
            animateOut();
            $(this).animate({"height":"40px"},100);
            return;
        } else return;
    });// end check
    
    gotID = $(this).attr('id');
    
    $('#'+gotID+'').animate({"height" : "188px"},300,cursorOut);   
    
}
var animateOut = function animateOut() {
    var loadUrl = "index.php?r=bookmark/getBookmarkTitle"; 
    $('#'+gotID+'').children().html(ajax_load).load(loadUrl,{'id':gotID},function(response, status, xhr) {
        if (status == "error") {
            var msg = "error loading title: ";
            $(this).html(msg + xhr.status + " ");
        }
  });
    $(this).animate({"height":"40px"}, 100);
    
}
var cursorOut = function cursorOut() {
    var loadUrl = "index.php?r=bookmark/getBookmarkOptions"; 
    $('#'+gotID+'').children().html(ajax_load).load(loadUrl,{'id':gotID},function(response, status, xhr) {
        if (status == "error") {
            var msg = "error loading Bookmark: ";
            $(this).html(msg + xhr.status + " ");
        }
  });
    $(this).mouseleave(animateOut);
    
}

$(document).ready(function(){
// create custom animation algorithm for jQuery called "bouncy"
    $.easing.bouncy = function (x, t, b, c, d) {
        var s = 1.70158;
        if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
        return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
    }

    // create custom tooltip effect for jQuery Tooltip
    $.tools.tooltip.addEffect("bouncy",

  // opening animation
  function(done) {
    this.getTip().animate({opacity: 0.7, top: '+=20'}, 300, 'bouncy', done).show();
  },

  // closing animation
  function(done) {
    this.getTip().animate({opacity: 0,top: '-=20'}, 200, 'bouncy', function()  {
      $(this).hide();
      done.call();
    });
  }
    );
    // the simple tooltip effect with an offset which let it appears in front of 
    // the imagebox. 
    
    $("div.view").tooltip({ offset: [-19, -250], opacity: 0.7,effect: 'bouncy'});
    
    // when click event is captured animateIn callback is starting look above
    $(".tooltip").click(animateIn);
    
    /*$(".optionsDelete").click(function(){
        
        gotID = $(this).closest('.tooltip').attr('id');
        var loadUrl = "index.php?r=bookmark/delete&id="+gotID+""; 
        $('.message').load(loadUrl,{'id':gotID},function(response, status, xhr) {
            if (status == "error") {
                var msg = "error deleting: ";
                $(this).html(msg + xhr.status + " ");
                }
            if (status == "success") {
                $(this).closest('.tooltip').hide();
                $('#'+gotID+'').prev().slideUp(500,function(){
                    $(this).next().remove();
                    $(this).remove();
                });
            }
        });
    });*/
    
    /*$(".optionsEditImage").click(function(){
        $("#dialogEditImage").dialog('open');
        gotID = $(this).closest('.tooltip').attr('id');
        var loadUrl = "index.php?r=bookmark/GetBigImage&id="+gotID+"";
        $("#dialogEditImage").append('<img id="bigImage"/>');
        $("#bigImage").attr('src',loadUrl);
    });
    $("#bigImage").axzoomer({
	'maxZoom':4,
	'opacity':0.5,
	'sensivity':10
    });*/
});
</script>

