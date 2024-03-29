<?php

namespace maddoger\user\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class RequestPasswordResetTokenForm extends Model
{
	public $email;
	public $verifyCode;


	private $_user = false;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			// email is required and has to be a valid email address
			['email', 'required'],
			['email', 'email'],
			// user has to be exist
			['email', 'validateUser'],
			// verifyCode needs to be entered correctly
			['verifyCode', 'captcha', 'captchaAction' => 'user/backend-auth/captcha'],
		];
	}

	/**
	 * Validates the user.
	 */
	public function validateUser()
	{
		$user = User::findByEmail($this->email);
		if (!$user) {
			$this->addError('username', Yii::t('maddoger/user', 'User with this email does not exist.'));
		}
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'email' => Yii::t('maddoger/user', 'Email'),
			'verifyCode' => Yii::t('maddoger/user', 'Verification code'),
		];
	}
}
