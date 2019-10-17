<?php
/* @var $this yii\web\View */

use yii\grid\GridView;

?>

    <h3>История операций</h3>
    <br/>
<?
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'date',
        'purpose',
        [
            'attribute' => 'diff_rub',
            'format' => function ($value) {
                return ($value > 0 ? '+' : '') . $value;
            }
        ],
        [
            'attribute' => 'diff_bonus',
            'format' => function ($value) {
                return ($value > 0 ? '+' : '') . $value;
            }
        ],
        'balance_rub',
        'balance_bonus',
    ]
]);
?>