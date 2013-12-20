<?php

namespace rusporting\user\controllers;

use Yii;
use yii\web\Controller;
use yii\web\AccessControl;
use rusporting\user\models\LoginForm;
use rusporting\user\models\User;
use yii\web\Response;

class AuthController extends Controller
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['login'],
						'allow' => true,
					],
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],

				],
			],
		];
	}

	public function actionLogin()
	{
		if (!\Yii::$app->user->isGuest) {
			$this->goBack();
		}

		$model = new LoginForm();
		if ($model->load($_POST) && $model->login()) {
			return $this->goBack();
		} else {
			return $this->render('login', [
				'model' => $model,
			]);
		}
	}

	public function actionLogout()
	{
		Yii::$app->user->logout();
		return $this->goHome();
	}
}
