<?php

namespace frontend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('viewInfo')) {
            return $this->redirect(['user/login']);
        }

        $user = Yii::$app->user->identity;

        $dataProvider = new ActiveDataProvider([
            'query' => $user->getTransactions(),
            'pagination' => [
                'pageSize' => 3,
            ],
        ]);

        return $this->render('index', [
            'user' => $user,
            'dataProvider' => $dataProvider
        ]);
    }
}
