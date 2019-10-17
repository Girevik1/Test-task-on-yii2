<?php

use yii\widgets\LinkPager;

?>
<div>
    <center>
        <h1>
            Здравствуйте, <?php if (Yii::$app->user->identity) {
                echo ucfirst(Yii::$app->user->identity->username);
            } ?>
        </h1>
    </center>
    <br>
    <h2>Ваш рублевый баланс: <?= $user->balance_rub ?></h2>
    <h2>Ваш бонусный баланс: <?= $user->balance_bonus ?></h2>
</div>
<hr>

<center>
    <?= $this->render('/partials/_transactions_list', ['dataProvider' => $dataProvider]) ?>
</center>


