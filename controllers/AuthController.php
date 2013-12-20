<?php

namespace rusporting\user\controllers;

use Yii;
use yii\web\Controller;
use yii\web\AccessControl;
use rusporting\user\models\LoginForm;
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
						'actions' => ['logout', 'index'],
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
			$this->goHome();
		}

		$model = new LoginForm();
		if ($model->load($_POST) && $model->login()) {

			$text = 'Ура! '.Yii::$app->user->getIdentity()->username;

			var_dump(Yii::$app->getSession());


			return $text;


			//return $this->goBack();
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
