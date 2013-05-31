<?php
// register scripts
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/toggle-search.js');
?>

<h1>Manage Categories</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php if (Yii::app()->user->hasFlash('success')) : ?>
    <div class="alert-box success">
        <?php echo Yii::app()->user->getFlash('success'); ?>
        <a href="" class="close">&times;</a>
    </div>
<?php endif; ?>

<p><a class="search-button">Advanced Search</a></p>
<div class="search-form">
    <?php $this->renderPartial('_search',array(
        'model' => $model,
    )); ?>
</div>

<?php $this->widget('GridView', array(
    'dataProvider' => $model->search(),
    'cssFile'      => false,
    'columns'      => array(
        'name',
        'parentName',
        array(
            'class' => 'CDataColumn',
            'name' => 'Entries',
            'value' => 'count($data->entries)',
        ),
        array(
            'class' => 'ButtonColumn',
            'buttons' => array(
                'view' => array(
                    'url' => 'array("entry/index", "Entry[categoryIds][]" => $data->id)',
                )
            )
        ),
    ),
)); ?>