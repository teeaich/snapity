<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
<div class="view">

  
 
  
  <?php 
    $image_href = CHtml::encode($data->link);
    $imageLink = 'images/bk_preview/'.$data->pre_image;
    
    echo CHtml::openTag('a',array(
      'href'=>$image_href,
      'target'=>'_blank',
                  ));
    echo CHtml::openTag('img',array(
      'class'=>$data->pre_image === 'loading.gif'? '':'corner iradius5',
      'src'=>$imageLink,
                  ));
    echo CHtml::closeTag('img');
    echo CHtml::closeTag('a'); 
    ?>


    
 
</div>
<div class="tooltip" id="<?php echo CHtml::encode($data->id);?>">
    <div class="tooltipInner"  >
    <?php 
    echo CHtml::encode($data->title);
    ?>
       <!-- <div class="button">x</div>-->
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
});
</script>