<?php
echo CHtml::encode($model->title);
?>
<?php echo CHtml::ajaxLink('Delete',CHtml::normalizeUrl(array('bookmark/delete')),array('type'=>'get',
            'data'=> array('id'=>$model->id),
            ),array('id'=>'send-link-'.uniqid(),
                                       
                                       ))
                                                                                                  
                              ?>
