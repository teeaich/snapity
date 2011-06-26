<!-- dont influences the ajaxform at any case-->
<?php Yii::app()->clientscript->scriptMap['jquery.js'] = false;?>
<div id='dialogAjax' class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'bookmark-form',
  'enableAjaxValidation'=>true,
)); ?>

  <p class="note">Fields with <span class="required">*</span> are required.</p>

  <?php echo $form->errorSummary($model);?>

  <div class="row">
    <?php echo $form->labelEx($model,'title'); ?>
    <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>128)); ?>
    <?php echo $form->error($model,'title'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'link'); ?>
    <?php echo $form->textField($model,'link',array('size'=>60,'maxlength'=>128)); ?>
    <?php echo $form->error($model,'link'); ?>
  </div>
  
  <div class="row buttons">
    <?php echo CHtml::ajaxSubmitButton('Create Job',CHtml::normalizeUrl(array('bookmark/ajaxcreate')),array('type'=>'post',
            'dataType'=>'json',
            'success'=>"js:function(data) 
                             {
                               if (data.status == 'failure') 
                                   {
                                      $('#dialogBookmark').html(data.div);
                                      alert ('mein Titel','status failure');
                                   }
                               if (data.status == 'success')
                                   {
                                      $('#dialogBookmark').html(data.div);                                                 
                                      alert ('Titel','else');
                                      $('#dialogBookmark').fadeOut(900);
                                      $('#dialogBookmark').dialog('close');                                                            
                                                                                                  
                                   }                                                             
                                                                                             
                               
                             }"),array('id'=>'send-link-'.uniqid()));
                                                                                                  
                              ?>
                                                   
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<!--
                                {
                                    alert( 'mein Titel', 'meine Nachricht' ); 
                                                                                                                                                         $('#dialogBookmark').dialog('close');
                                    $('#dialogAjax div.form').html(data.div);
                                    // Here is the trick: on submit-> once again this function!
                                                   //$('#dialogBookmark div.divForForm form').submit(add                                                                  
                                }
                                else
                                    {
                                        $('#dialogBookmark div.form').html(data.div);
                                        setTimeout(\"$('#dialogBookmark').dialog('close') \",3000);
                                    }
                                    }"));
                           -->