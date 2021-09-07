<?php

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Diagram */

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\main\models\Diagram;

$this->title = Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAM') . ' - ' . $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAMS'),
    'url' => ['diagrams']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<?= $this->render('_modal_form_diagrams', ['model' => $model]); ?>

<div class="view-diagram">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-blackboard"></span> ' .
            Yii::t('app', 'BUTTON_OPEN_DIAGRAM'),
            ['/eete/tree-diagrams/visual-diagram/', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' .
            Yii::t('app', 'BUTTON_UPDATE'),
            ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-import"></span> ' .
            Yii::t('app', 'BUTTON_IMPORT'),
            ['import', 'id' => $model->id], ['class' => 'btn btn-primary']
        ) ?>
        <?= Html::a('<span class="glyphicon glyphicon-export"></span> ' .
            Yii::t('app', 'BUTTON_EXPORT'),
            ['visual-diagram', 'id' => $model->id], ['data' => ['method' => 'post'], 'class' => 'btn btn-primary']
        ) ?>
        <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> ' .
            Yii::t('app', 'BUTTON_UPLOAD_ONTOLOGY'),
            ['upload-ontology', 'id' => $model->id], ['class' => 'btn btn-primary']
        ) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' .
            Yii::t('app', 'BUTTON_DELETE'), ['#'], [
            'class' => 'btn btn-danger',
            'data-toggle' => 'modal',
            'data-target' => '#removeDiagramModalForm'
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'created_at',
                'format' => ['date', 'dd.MM.Y HH:mm:ss']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'dd.MM.Y HH:mm:ss']
            ],
            [
                'attribute' => 'author',
                'value' => $model->user->username,
            ],
            'name',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getTypeName();
                },
                'filter' => Diagram::getTypesArray(),
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getStatusName();
                },
                'filter' => Diagram::getStatusesArray(),
            ],
//            [
//                'attribute' => 'mode',
//                'format' => 'raw',
//                'value' => function($data) {
//                    return $data->getModesName();
//                },
//                'filter' => TreeDiagram::getModesArray(),
//            ],
//            [
//                'attribute' => 'tree_view',
//                'format' => 'raw',
//                'value' => function($data) {
//                    return $data->getTreeViewName();
//                },
//                'filter' => TreeDiagram::getTreeViewArray(),
//            ],
            [
                'attribute' => 'correctness',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getCorrectnessName();
                },
                'filter' => Diagram::getCorrectnessArray(),
            ],
            'description',
        ],
    ]) ?>

</div>