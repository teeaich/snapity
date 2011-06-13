<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
<script src="http://cdn.jquerytools.org/1.2.5/all/jquery.tools.min.js"></script>
<div class="view">

  
 
  
  <?php 
    $image_href = CHtml::encode($data->link);
    $imageLink = 'images/bk_preview/'.$data->pre_image;
       
    echo CHtml::openTag('a',array(
      'href'=>$image_href,
      'target'=>'_blank',
                  ));
    echo CHtml::openTag('img',array(
      'class'=>'corner iradius5',
      'src'=>$imageLink,
                  ));
    echo CHtml::closeTag('img');
    echo CHtml::closeTag('a'); 
    ?>


    
 
</div>
<div class="tooltip">
    <div id='tooltip'>
    <?php 
    echo CHtml::encode($data->title);
    ?>
        <div class="button">x</div>
    </div>  


</div>
<script type="text/javascript">
    

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
   $(".view",this).tooltip({ offset: [-16, -250], opacity: 0.7,effect: 'bouncy'});

});


</script>