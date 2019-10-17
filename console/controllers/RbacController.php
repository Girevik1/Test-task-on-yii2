<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();

        $admin = $auth->createRole('admin');
        $user = $auth->createRole('user');

        $auth->add($admin);
        $auth->add($user);

        $viewAdminPage = $auth->createPermission('viewAdminPage');
        $viewAdminPage->description = 'Просмотр админки';

        $viewInfo = $auth->createPermission('viewInfo');
        $viewInfo->description = 'Просмотр операций';

        $auth->add($viewAdminPage);
        $auth->add($viewInfo);

        $auth->addChild($user, $viewInfo);

        $auth->addChild($admin, $user);

        $auth->addChild($admin, $viewAdminPage);

        $auth->assign($admin, 1);
    }

}