<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
$js = <<<JS
$(document).ready(function () {
    // Handler for .ready() called.
    window.setTimeout(function () {
        location.href = "/";
    }, 5000);
});
JS;

$this->registerJs( $js, $position = yii\web\View::POS_READY, $key = null );

$this->title = 'Спасибо за заявку!';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <h1><?= Html::encode($this->title) ?></h1>
    <hr>
    <div class="row">
        <div class="col-lg-12 text-center">
            <h2>Мы получили Вашу заявку, в ближайшее время мы ознакомимся с ней и по необходимости свяжемся с Вами.</h2>
        </div>
    </div>

</div>
