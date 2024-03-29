<?php //Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->baseUrl.'/css/manager.css');?>
<div class="row white-block">
<?php
/* @var $this CategoriesController */
/* @var $dataProvider CActiveDataProvider */

//$this->breadcrumbs=array(
//	Yii::t('site','Catalog'),
//);

$this->menu=array(
	array('label'=>Yii::t('site','Catalog'), 'url'=>array('create')),
	array('label'=>Yii::t('site','Manage Catalog'), 'url'=>array('admin')),
);
?>

<h1><?echo Yii::t('site','Catalog');?></h1>

<?php
$criteria = new CDbCriteria();
$criteria->compare('field_type','LIST');
$list = CHtml::listData(ProjectField::model()->findAll($criteria),'varname','title');
$list = array_merge($list,CHtml::listData(ProfileField::model()->findAll($criteria),'varname','title'));
echo CHtml::link(CHtml::encode('All'), array('index')).'   ';
foreach($list as $key => $value){
	 echo CHtml::link(CHtml::encode($value), array('index', 'field_varname'=>$key)).'   ';
}

$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
</div>