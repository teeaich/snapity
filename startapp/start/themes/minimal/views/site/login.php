<?php Yii::app()->clientscript->scriptMap['jquery.js'] = false;?>
<h1>Login</h1>

<p>Please fill out the following form with your login credentials:</p>


<div id="dialogLoginView" class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'login-form',
  'enableAjaxValidation'=>true,
  
  'clientOptions'=>array(
  'validateOnSubmit'=>true,
  
))); ?>

  <p class="note">Fields with <span class="required">*</span> are required.</p>

  <div class="row">
    <?php echo $form->labelEx($model,'username'); ?>
    <?php echo $form->textField($model,'username'); ?>
    <?php echo $form->error($model,'username'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'password'); ?>
    <?php echo $form->passwordField($model,'password'); ?>
    <?php echo $form->error($model,'password'); ?>
    <p class="hint">
      Hint: You may login with <tt>demo/demo</tt> or <tt>admin/admin</tt>.
    </p>
  </div>

  <div class="row rememberMe">
    <?php echo $form->checkBox($model,'rememberMe'); ?>
    <?php echo $form->label($model,'rememberMe'); ?>
    <?php echo $form->error($model,'rememberMe'); ?>
  </div>
  <?php echo CHtml::Link('Login with Google',CHtml::normalizeUrl(array('site/OpenId')));?>
  <?php echo CHtml::ajaxLink('Login with Google',CHtml::normalizeUrl(array('site/OpenId')),array(
                             'dataType'=>'json',
                             'success'=>"js:function(data) {
                                 if (data.status == 'success') {
                                    $('#dialogLogin').html(data.div);
                                 }
                              }"
                            ),array('id'=>'send-link-'.uniqid()));?>
  <div class="row buttons">
    <?php echo CHtml::ajaxSubmitButton('Login',CHtml::normalizeUrl(array('site/ajaxLogin')),array(
                             'type'=>'post',
                             'dataType'=>'json',
                             'update'=>'#dialogLogin'),array('id'=>'send-link-'.uniqid())); ?>
</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

