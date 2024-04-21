<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\FeedbackForm */
/* @var $form ActiveForm */

$this->registerJsFile(
    '@web/js/voiceform.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerCssFile("@web/css/index.css");


$this->title = 'Arena обратная связь';
?>



<div class='ripple-background'>
  <div class='circle xxlarge shade1'></div>
  <div class='circle xlarge shade2'></div>
  <div class='circle large shade3'></div>
  <div class='circle mediun shade4'></div>
  <div class='circle small shade5'></div>
</div>

<div class="container rounded shadow-lg border bg-light">
<br>
<br>
<br>
<h1>Обратная связь</h1>
<h4>Возникли вопросы или предложения? Заполните форму ниже, мы свяжемся с Вами...</h4>
<i>Поля, отмеченные <b><font color="red">*</font></b> - необходимы для заполнения</i> 
<?php $form = ActiveForm::begin([
    'id' => 'sendform', 
    'action' => '/site/receivefeedback', 
    // 'enableAjaxValidation' => false,
    // 'enableClientValidation' => false,
    'method' => 'POST',
    // 'clientOptions' => [
        // 'validateOnSubmit' => false,
    // ]
    ]); ?>

<div class="row">
    <div class="col-lg-4">
        <?= $form->field($model, 'name') ?>
    </div>
    <div class="col-lg-4">
        <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::class, [
    'mask' => '999 99-99-99',
]) ?>
    </div>
    <div class="col-lg-4">
        <?= $form->field($model, 'email') ?>
    </div>
</div>

<p>
  <a class="btn btn-primary" data-toggle="collapse" id="audiocolapse" href="#collapseVoice" role="button" aria-expanded="false" aria-controls="collapseVoice">
    Записать аудио сообщение
  </a>
</p>
<div class="collapse" id="collapseVoice">
  <div class="card card-body">
      <div class="row">
        <div class="col-lg-2">
            <button type="button" href="#" id="start" class="btn btn-md btn-danger">Запись</a>
        </div>
        <div class="col-lg-2">
            <button type="button" href="#" id="stop" class="btn btn-md btn-warning">Стоп</button>
        </div>
        <div class="col-lg-8" id="recordstatus">
              <button class="btn btn-primary btn-block" type="button" disabled>
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                В ожидании...
              </button>
        </div>
      </div>
    
    
  </div>
</div>

<?php //echo $form->field($model, 'voicefile')->fileInput(['id' => 'recordedvoice'])->label(false) ?>


<?= $form->field($model, 'subject')->dropDownList(Yii::$app->params['feedbackthemes'], ['prompt' => 'Выберите тему сообщения...']) ?>
<div class="row">
    <div class="col-lg-6">
        <?= $form->field($model, 'body')->textArea(['placeholder' => 'Сообщение...', 'id' => 'body']) ?>
    </div>
    <div class="col-lg-6">
    <label class="control-label" for="reCaptcha">Проверка на робота</label>
        <?= \himiklab\yii2\recaptcha\ReCaptcha2::widget([
            'name' => 'reCaptcha',
            'siteKey' => 'GOOGLE_SITE_KEY_V2', // unnecessary is reCaptcha component was set up
            'widgetOptions' => ['class' => 'col-sm-offset-3'],
        ])
        ?>
    </div>
</div>

<div class="col-lg-12">
    <p id="erm" style="display:none">
            123
    </p>
</div>

<div class="form-group">
    <?php // echo Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-lg btn-block', 'id' => 'submintbutton']) ?>
    <a id="testsend" class="btn btn-primary btn-lg btn-block">Отправить</a>
</div>
<?php ActiveForm::end(); ?>
<br>
</div>
