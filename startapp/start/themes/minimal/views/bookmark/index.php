<script type="text/javascript" src="/startapp/start/themes/minimal/js/corner.js"></script>
<script src="http://cdn.jquerytools.org/1.2.5/all/jquery.tools.min.js"></script>
<?php
  /*$this->breadcrumbs=array(
  'Bookmarks',
  );*/
$this->menu=array(
  array('label'=>'Create Bookmark', 'url'=>array('create')),
  array('label'=>'Create AjaxBookmark','url'=>array('ajaxcreate')),
  array('label'=>'Manage Bookmark', 'url'=>array('admin')),
);
?>

<!--<h1>Bookmarks</h1>-->

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
                                       'class'=>'createButton',
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

<script type="text/javascript">
var animateIn = function animateIn() {
    gotID = $(this).attr('id');
    
    $('#'+gotID+'').animate({"height" : "188px"},300,cursorOut);   
    
}
var animateOut = function animateOut() {
    var ajax_load = "<img src='themes/minimal/images/load.gif' alt='loading...' />";  
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
    var ajax_load = "<img src='themes/minimal/images/load.gif' alt='loading...' />";  
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
    
    $("div.view").tooltip({ offset: [-16, -250], opacity: 0.7,effect: 'bouncy'});
    $.ajaxSetup ({  
        cache: false  
    });  
    // when click event is captured animateIn callback is starting look above
    $(".tooltip").click(animateIn);
});
</script>

