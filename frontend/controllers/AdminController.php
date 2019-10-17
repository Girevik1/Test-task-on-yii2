<?php

namespace frontend\controllers;

use frontend\models\forms\TransactionForm;
use Yii;
use yii\web\Controller;
use frontend\models\User;
use frontend\models\search\UserSearch;
use yii\data\ActiveDataProvider;
use frontend\components\NotEnoughMoneyException;

class AdminController extends Controller
{
    public function actionIndex()
    {
        if (!Yii::$app->user->can('viewAdminPage')) {
            return $this->redirect(['site/index']);
        }

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionEdit($id)
    {
        if (!Yii::$app->user->can('viewAdminPage')) {
            return $this->redirect(['site/index']);
        }

        $user = User::findOne($id);

        $model = new TransactionForm();

        $model->user_id = $user->id;

        try {
            if ($model->load(Yii::$app->request->post()) && $transaction = $model->save()) {
                Yii::$app->session->setFlash('success', 'Операция успешно добавлена');
                return $this->refresh();
            }
        } catch (NotEnoughMoneyException $user_error) {
            Yii::$app->session->setFlash('error', 'Недостаточно средств для списания');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $user->getTransactions(),
            'pagination' => [
                'pageSize' => 3,
            ],
        ]);

        return $this->render('edit', [
            'user' => $user,
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
}