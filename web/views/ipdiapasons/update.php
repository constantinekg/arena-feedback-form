<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ipdiapasons */

$this->title = 'Редакция сети: ' . $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Диапазоны сетей клубов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->description, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="ipdiapasons-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
