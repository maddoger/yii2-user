<?php

namespace rusporting\user\controllers;

use Yii;
use yii\web\AccessControl;
use yii\web\BadRequestHttpException;
use yii\helpers\Security;

use rusporting\core\FrontendController;
use rusporting\user\models\LoginForm;
use rusporting\user\models\User;
use rusporting\user\models\RequestPasswordResetTokenForm;

class BackendAuthController extends FrontendController
{
	public function init()
	{
		parent::init();
		$this->layout = $this->module->authBackendLayout;
	}

	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['logout'],
				'rules' => [
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}

	public function actions(){
		return array(
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
			],
		);
	}

	public function actionLogin()
	{
		if (!\Yii::$app->user->isGuest) {
			return $this->goBack();
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

	public function actionRequestPasswordReset()
	{
		$model = new RequestPasswordResetTokenForm();
		if ($model->load($_POST) && $model->validate()) {
			if ($this->sendPasswordResetEmail($model->email)) {
				Yii::$app->getSession()->setFlash('success', Yii::t('rusporting/user', 'Check your email for further instructions.'));
				return $this->goHome();
			} else {
				Yii::$app->getSession()->setFlash('error', Yii::t('rusporting/user', 'There was an error sending email.'));
			}
		}
		return $this->render('requestPasswordResetToken', [
			'model' => $model,
		]);
	}

	public function actionResetPassword($token)
	{
		$model = User::find([
			'password_reset_token' => $token,
			'status' => User::STATUS_ACTIVE,
		]);

		if (!$model) {
			throw new BadRequestHttpException(Yii::t('rusporting/user', 'Wrong password reset token.'));
		}

		$model->scenario = 'resetPassword';
		if ($model->load($_POST) && $model->save()) {
			Yii::$app->getSession()->setFlash('success', Yii::t('rusporting/user', 'New password was saved.'));
			return $this->goHome();
		}

		return $this->render('resetPassword', [
			'model' => $model,
		]);
	}

	private function sendPasswordResetEmail($email)
	{
		$user = User::find([
			'status' => User::STATUS_ACTIVE,
			'email' => $email,
		]);

		if (!$user) {
			return false;
		}

		$user->password_reset_token = Security::generateRandomKey();
		if ($user->save(false)) {
			return \Yii::$app->mail->compose($this->module->passwordResetTokenEmail, ['user' => $user])
								   ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
								   ->setTo($email)
								   ->setSubject(Yii::t('rusporting/user', 'Password reset for {name}', ['name'=> \Yii::$app->name]))
								   ->send();
		}
		return false;
	}
}
