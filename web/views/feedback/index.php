<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\daterange\DateRangePicker;
use yii\bootstrap4\Modal;
/* @var $this yii\web\View */
/* @var $searchModel app\models\FeedbackQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */
use yii\widgets\BootstrapLinkPager;

$this->title = 'Заявки';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="feedback-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= Html::a('Сбросить вид', ['/feedback'], ['class' => 'btn btn-lg btn-primary']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'format' => 'text',
                'contentOptions' => ['style' => 'font-size:14px;'],
                'value' => function ($model) {
                    return Html::encode($model->name);
                }
            ],
            [
                'attribute' => 'phone',
                'format' => 'text',
                'contentOptions' => ['style' => 'font-size:14px;'],
                'value' => function ($model) {
                    return Html::encode($model->phone);
                }
            ],
            [
                'attribute' => 'email',
                'format' => 'email',
                'contentOptions' => ['style' => 'font-size:14px;'],
            ],
            [
                'attribute' => 'subject',
                'format' => 'text',
                'contentOptions' => ['style' => 'font-size:14px;'],
                'filter' => Yii::$app->params['feedbackthemes'],
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'Все'],
                'value' => function ($model) {
                    return Yii::$app->params['feedbackthemes'][$model->subject];
                }
            ],
            [
                'attribute' => 'body',
                'format' => 'text',
                'contentOptions' => ['style' => 'font-size:14px;'],
                'value' => function ($model) {
                    $firsttenwords = implode(' ', array_slice(explode(' ', trim($model->body)), 0, 10, true));
                    return Html::encode($firsttenwords.'...');
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
                'filter' => ['0' => 'Нет', '1' => 'Есть'],
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'Все'],
                'value' => function($model) {
                    $filepath = Yii::getAlias('@app/web/uploadedogg') . DIRECTORY_SEPARATOR . $model->voicefile . '.wav';
                    if (file_exists($filepath)) {
                        return '<a href="/uploadedogg/'.$model->voicefile.'.wav" target=”_blank” rel=”noopener noreferrer”><img src="/img/mic.png"></a>';
                    }
                    else {
                        return 'нет';
                    }
                },
                'format' => 'raw',
            ],
            // [
            //     'attribute' => 'created_at',
            //     'format' => 'datetime',
            // ],

            // 'created_at:datetime',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'contentOptions' => ['style' => 'font-size:14px;'],
                'options' => [
                    'format' => 'YYYY-MM-DD',
                    ],
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => ([       
                    'attribute' => 'created_at',
                    'presetDropdown' => true,
                    'convertFormat' => false,
                    'pluginOptions' => [
                    'separator' => ' - ',
                    'format' => 'YYYY-MM-DD',
                    'locale' => [
                            'format' => 'YYYY-MM-DD'
                        ],
                    ],
                    'pluginEvents' => [
                    "apply.daterangepicker" => "function() { apply_filter('only_date') }",
                    ],
                ])
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}  {delete}',
            ],
        ],
    ]); ?>

<?php echo BootstrapLinkPager::widget([
    'pagination'=>$dataProvider->pagination,
]); ?>

</div>
