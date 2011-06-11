<script type="text/javascript" src="/startapp/start/themes/minimal/js/corner.js"></script>
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

<!--<?php echo CHtml::link('Create Bookmark', "",  // the link for open the dialog
    array(
        'style'=>'cursor: pointer; text-decoration: underline;',
        'onclick'=>"$('#dialogBookmark').dialog('open');addBookmark();"));?>
-->
   
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
        'height'=>470,
        'close' => array('js:function(){$(this).dialog("destroy")}'),
      'buttons' => array('js:function(){$(this).dialog("close")}'),

    ),
));?>
<div id="divForForm"></div>
 
<?php $this->endWidget();?>
 

