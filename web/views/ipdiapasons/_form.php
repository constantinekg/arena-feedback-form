<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Ipdiapasons */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ipdiapasons-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ipaddr')->textInput(['maxlength' => true, 'placeholder' => '192.168.0.0']) ?>

    <?= $form->field($model, 'netmask')->dropDownList([
                '32'	=> '255.255.255.255 => /32',
                '31'	=> '255.255.255.254 => /31',
                '30'	=> '255.255.255.252 => /30',
                '29'	=> '255.255.255.248 => /29',
                '28'	=> '255.255.255.240 => /28',
                '27'	=> '255.255.255.224 => /27',
                '26'	=> '255.255.255.192 => /26',
                '25'	=> '255.255.255.128 => /25',
                '24'	=> '255.255.255.0  => /24',
    ], ['prompt' => 'Выберите маску подсети...']) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true, 'placeholder' => 'Где то там...']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-block btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
