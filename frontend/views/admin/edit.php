<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>

<?php $this->title = 'Зачисление или списание' ?>

<div>
    <center>
        <h3>Зачисление или списание по определенному значению</h3>
    </center>
    <br>
</div>
<div class="row">
    <div class="col-xs-12 col-md-3">
        <p>Пользователь: <?= ucfirst($user->username); ?></p>
        <p>Рублевый баланс: <?= $user->balance_rub ?></p>
        <p>Бонусный баланс: <?= $user->balance_bonus ?></p>
        <a href="<?php echo Url::to(['admin/index']); ?>" class="btn btn-primary">Назад к списку</a>
    </div>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-transaction']); ?>

            <?= $form->field($model, 'purpose')->textInput() ?>

            <?= $form->field($model, 'sum')->textInput(['type' => 'number', 'step' => '0.01']) ?>

            <div class="form-group">
                <?= Html::submitButton('Создать', ['class' => 'btn btn-primary', 'name' => 'transaction-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

<hr>
<center>
    <?= $this->render('/partials/_transactions_list', ['dataProvider' => $dataProvider]) ?>
</center>


