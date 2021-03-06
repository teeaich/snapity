<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'bookmark-form',
  'enableAjaxValidation'=>false,
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
  <!--
  <div class="row">
    <?php echo $form->labelEx($model,'pre_image'); ?>
    <?php echo $form->textField($model,'pre_image',array('size'=>60,'maxlength'=>128)); ?>
    <?php echo $form->error($model,'pre_image'); ?>
  </div>

  

  <div class="row">
    <?php echo $form->labelEx($model,'create_time'); ?>
    <?php echo $form->textField($model,'create_time'); ?>
    <?php echo $form->error($model,'create_time'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'user_bk_id'); ?>
    <?php echo $form->textField($model,'user_bk_id'); ?>
    <?php echo $form->error($model,'user_bk_id'); ?>
  </div>
-->
  <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'ajaxCreate' : 'Save'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->