<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Feedback */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="feedback-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'А ты уверен в том, что ты действительно желаешь грохнуть эту заявку?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'name',
                'format' => 'text',
                'value' => function ($model) {
                    return Html::encode($model->name);
                }
            ],
            [
                'attribute' => 'phone',
                'format' => 'text',
                'value' => function ($model) {
                    return Html::encode($model->phone);
                }
            ],
            'email:email',
            [
                'attribute' => 'subject',
                'format' => 'text',
                'value' => function ($model) {
                    return Yii::$app->params['feedbackthemes'][$model->subject];
                }
            ],
            [
                'attribute' => 'body',
                'format' => 'text',
                'value' => function ($model) {
                    if ($model->body == '') {
                        return 'Текстовое сообщение отсутствует';
                    }
                    else {
                        return Html::encode($model->body);
                    }
                }
            ],
            [
                'attribute' => 'ipaddr',
                'format' => 'raw',
                'contentOptions' => ['style' => 'font-size:14px;'],
                'value' => function ($model) use ($ipdiapasons) {
                    foreach ($ipdiapasons as $ipdiapason) {
                        if (Yii::$app->utils->cidr_match($model->ipaddr, $ipdiapason['net'])) {
                            return $model->ipaddr . '<br>' . $ipdiapason['description'];
                        }
                        else {
                            return $model->ipaddr;
                        }
                    }
                }
            ],
            [
                'attribute' => 'voicefile',
                'value' => function($model) {
                    $filepath = Yii::getAlias('@app/web/uploadedogg') . DIRECTORY_SEPARATOR . $model->voicefile . '.wav';
                    if (file_exists($filepath)) {
                        return '<audio controls="controls"><source src="/uploadedogg/' . $model->voicefile . '.wav" type="audio/ogg"></source>
                        Your browser does not support the audio element.</audio>';
                    }
                    else {
                        return 'нет';
                    }
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
            ],
        ],
    ]) ?>

</div>

<!-- <audio id="audio" controls="controls">
        <source id="wavSource" src="" type="audio/wav"></source>
        Your browser does not support the audio format.
    </audio> -->