<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ipdiapasons */

$this->title = 'Создать диапазон';
$this->params['breadcrumbs'][] = ['label' => 'Диапазоны сетей клубов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ipdiapasons-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-6">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
        <div class="col-lg-6">
        Размер - подсеть - /слэш
        1	- 255.255.255.255	- /32<br>
        2	- 255.255.255.254	- /31<br>
        4	- 255.255.255.252	- /30<br>
        8	- 255.255.255.248	- /29<br>
        16	- 255.255.255.240	- /28<br>
        32	- 255.255.255.224	- /27<br>
        64	- 255.255.255.192	- /26<br>
        128	- 255.255.255.128	- /25<br>
        256	- 255.255.255.0 	- /24<br>
        </div>
    </div>



</div>
