<?php

namespace rusporting\user\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
	public $username;
	public $password;
	public $rememberMe = true;

	private $_user = false;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			// username and password are both required
			[['username', 'password'], 'required'],
			// password is validated by validatePassword()
			['password', 'validatePassword'],
			// rememberMe must be a boolean value
			['rememberMe', 'boolean'],
		];
	}

	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 */
	public function validatePassword()
	{
		$user = $this->getUser();
		if (!$user) {
			$this->addError('username', Yii::t('rusporting\user', 'User with this username or email does not exist.'));
		}
		if (!$user || !$user->validatePassword($this->password)) {
			$this->addError('password', Yii::t('rusporting\user', 'Incorrect username or password.'));
		}
	}

	/**
	 * Logs in a user using the provided username and password.
	 * @return boolean whether the user is logged in successfully
	 */
	public function login()
	{
		if ($this->validate()) {
			return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
		} else {
			return false;
		}
	}

	/**
	 * Finds user by [[username]] or [[email]]
	 *
	 * @return User|null
	 */
	private function getUser()
	{
		if ($this->_user === false) {
			$this->_user = User::findByUsernameOrEmail($this->username);
		}
		return $this->_user;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('rusporting\user', 'ID'),
			'username' => Yii::t('rusporting\user', 'Username'),
			'password' => Yii::t('rusporting\user', 'Password'),
			'rememberMe' => Yii::t('rusporting\user', 'Remember me'),
		];
	}
}
