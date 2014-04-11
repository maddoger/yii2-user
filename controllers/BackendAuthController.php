<?php

namespace maddoger\user\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\helpers\Security;

use maddoger\core\FrontendController;
use maddoger\user\models\LoginForm;
use maddoger\user\models\User;
use maddoger\user\models\RequestPasswordResetTokenForm;

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
				Yii::$app->getSession()->setFlash('success', Yii::t('maddoger/user', 'Check your email for further instructions.'));
				return $this->goHome();
			} else {
				Yii::$app->getSession()->setFlash('error', Yii::t('maddoger/user', 'There was an error sending email.'));
			}
		}
		return $this->render('requestPasswordResetToken', [
			'model' => $model,
		]);
	}

	public function actionResetPassword($token)
	{
		$model = User::findOne([
			'password_reset_token' => $token,
			'status' => User::STATUS_ACTIVE,
		]);

		if (!$model) {
			throw new BadRequestHttpException(Yii::t('maddoger/user', 'Wrong password reset token.'));
		}

		$model->scenario = 'resetPassword';
		if ($model->load($_POST) && $model->save()) {
			Yii::$app->getSession()->setFlash('success', Yii::t('maddoger/user', 'New password was saved.'));
			return $this->goHome();
		}

		return $this->render('resetPassword', [
			'model' => $model,
		]);
	}

	private function sendPasswordResetEmail($email)
	{
		$user = User::findOne([
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
								   ->setSubject(Yii::t('maddoger/user', 'Password reset for {name}', ['name'=> \Yii::$app->name]))
								   ->send();
		}
		return false;
	}
}
