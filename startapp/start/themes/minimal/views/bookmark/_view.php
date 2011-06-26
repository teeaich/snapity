<div class="view">

  
 
  
  <?php 
    $image_href = CHtml::encode($data->link);
    $imageLink = 'images/bk_preview/'.$data->snapshot;
    
    echo CHtml::openTag('a',array(
      'href'=>$image_href,
      'target'=>'_blank',
                  ));
    echo CHtml::openTag('img',array(
      'class'=>$data->snapshot === 'loading.gif'? '':'placeholder',
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
