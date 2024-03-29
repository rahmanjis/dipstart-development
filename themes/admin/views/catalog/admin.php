<?php
/* @var $this CategoriesController */
/* @var $model Categories */

$this->breadcrumbs=array(
	Yii::t('site','Catalog')=>array('index'),
	Yii::t('site','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('site','List Catalog'), 'url'=>array('index')),
	array('label'=>Yii::t('site','Create Catalog'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#catalog-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?=Yii::t('site','Manage Catalog')?></h1>

<p>
<?=Yii::t('site','You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.')?>
</p>

<?php echo CHtml::link(Yii::t('site','Advanced Search'),'#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'categories-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'field_varname',
		'cat_name',
		 array(
            'name' => 'parent_id',
            'type' => 'raw',
            'value' => 'Catalog::model()->performParent($data->parent_id)',
        ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
