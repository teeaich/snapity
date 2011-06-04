<?php
$this->breadcrumbs=array(
  'Bookmarks',
);

$this->menu=array(
  array('label'=>'Create Bookmark', 'url'=>array('create')),
  array('label'=>'Manage Bookmark', 'url'=>array('admin')),
);
?>

<h1>Bookmarks</h1>

<?php $this->widget('zii.widgets.CListView', array(
  'dataProvider'=>$dataProvider,
  'itemView'=>'_view',
  //'cssFile'=> dirname(Yii::app()->getBasePath()).'/css/classic/listview/styles.css',
  //nicht mehr notwenig da imagelink in bookmark controller nun definiert ist
  //'viewData'=> array (
  //'imageLink' => Config::getConfigValue('image_provider_link'),
  //                 ),
)); 
  
?>
<!--
<?php $this->widget('zii.widgets.grid.CGridView', array(
  'dataProvider'=>$dataProvider,
  //'itemView'=>'_view',
  

)); ?>
-->