<div class="form">

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
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Erstelle' : 'Sichern'); 
      ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->