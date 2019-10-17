<?php

use yii\helpers\Url;
use yii\grid\GridView;

?>
<center>
    <h3>Список пользователей</h3>
    <br/>
    <?
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'username',
            'balance_rub',
            'balance_bonus',
            [
                'label' => 'Операции',
                'format' => 'html',
                'value' => function ($user) {
                    return '<a href="' . Url::to(['admin/edit', 'id' => $user->id]) . '">Операции</a>';
                }
            ]
        ]
    ]);
    ?>
</center>


