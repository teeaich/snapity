<div class="view">

  <!--
  <b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
  <?php echo CHtml::encode($data->title); ?>
  <br />
    -->
  <?php 
    $image_href = CHtml::encode($data->link);
    $imageLink = 'images/'
                           .Yii::app()->user->id.'/'.$data->snapshot;
    echo CHtml::openTag('a',array(
      'href'=>$image_href,
      'target'=>'_blank',
                  ));
    echo CHtml::openTag('img',array(
      'class'=>'imagelink',
      'src'=>$imageLink,
                  ));
    echo CHtml::closeTag('img');
    echo CHtml::closeTag('a'); 
    ?>
 
</div>