<?php

namespace rusporting\user\models;

use rusporting\core\components\CoreActiveRecord;
use yii\helpers\Security;
use yii\web\IdentityInterface;

/**
 * @property integer $id
 * @property string $username
 * @property string $username_canonical
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $email_canonical
 * @property boolean $email_confirmed
 * @property string $first_name
 * @property string $last_name
 * @property string $nick_name
 * @property string $patronimic
 * @property string $short_name
 * @property string $full_name
 * @property string $date_of_birth
 * @property string $gender
 * @property string $facebook_uid
 * @property string $facebook_name
 * @property string $facebook_data
 * @property string $twitter_uid
 * @property string $twitter_name
 * @property string $twitter_data
 * @property string $gplus_uid
 * @property string $gplus_name
 * @property string $gplus_data
 * @property string $vk_uid
 * @property string $vk_name
 * @property string $vk_data
 * @property string $status
 * @property integer $create_time
 * @property integer $update_time
 */
class User extends CoreActiveRecord
{
	/**
	 * @var string the raw password. Used to collect password input and isn't saved in database
	 */
	public $password;

	const STATUS_DELETED = 0;
	const STATUS_ACTIVE = 10;

	const ROLE_USER = 10;

	public function behaviors()
	{
		return [
			'timestamp' => [
				'class' => 'yii\behaviors\AutoTimestamp',
				'attributes' => [
					CoreActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
					CoreActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
				],
			],
		];
	}

	/**
	 * Finds an identity by the given ID.
	 *
	 * @param string|integer $id the ID to be looked for
	 * @return IdentityInterface|null the identity object that matches the given ID.
	 */
	public static function findIdentity($id)
	{
		return static::find($id);
	}

	/**
	 * Finds active user by username (canonical)
	 *
	 * @param string $username
	 * @return null|User
	 */
	public static function findByUsername($username)
	{
		return static::find(['username_canonical' => $username, 'status' => static::STATUS_ACTIVE]);
	}

	/**
	 * Finds active user by email (canonical)
	 *
	 * @param string $email
	 * @return null|User
	 */
	public static function findByEmail($email)
	{
		return static::find(['email_canonical' => $email, 'status' => static::STATUS_ACTIVE]);
	}

	/**
	 * Finds active user by username or email (canonical)
	 *
	 * @param string $usernameOrEmail
	 * @return null|User
	 */
	public static function findByUsernameOrEmail($usernameOrEmail)
	{
		return static::find(['and',
			['or', ['username_canonical' => $usernameOrEmail], ['email_canonical' => $usernameOrEmail]],
			['status' => static::STATUS_ACTIVE]]
		);
	}

	/**
	 * @return int|string current user ID
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string current user auth key
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
	}

	/**
	 * @param string $authKey
	 * @return boolean if auth key is valid for current user
	 */
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}

	/**
	 * @param string $password password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return Security::validatePassword($password, $this->password_hash);
	}

	public function rules()
	{
		return [
			['username', 'filter', 'filter' => 'trim'],
			['username', 'required'],
			['username', 'string', 'min' => 2, 'max' => 255],

			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'unique', 'message' => 'This email address has already been taken.', 'on' => 'signup'],
			['email', 'exist', 'message' => 'There is no user with such email.', 'on' => 'requestPasswordResetToken'],

			['password', 'required'],
			['password', 'string', 'min' => 4],
		];

		/*
		 		return [
			[['username', 'username_canonical', 'auth_key', 'password_hash', 'email', 'email_canonical', 'email_confirmed', 'create_time', 'update_time'], 'required'],
			[['email_confirmed'], 'boolean'],
			[['date_of_birth'], 'safe'],
			[['facebook_data', 'twitter_data', 'gplus_data', 'vk_data', 'status'], 'string'],
			[['create_time', 'update_time'], 'integer'],
			[['username', 'username_canonical', 'password_hash', 'email', 'email_canonical', 'full_name', 'facebook_uid', 'facebook_name', 'twitter_uid', 'twitter_name', 'gplus_uid', 'gplus_name', 'vk_uid', 'vk_name'], 'string', 'max' => 255],
			[['auth_key', 'password_reset_token'], 'string', 'max' => 32],
			[['first_name', 'last_name', 'nick_name', 'patronimic'], 'string', 'max' => 50],
			[['short_name'], 'string', 'max' => 100],
			[['gender'], 'string', 'max' => 1]
		];
		 */
	}

	public function scenarios()
	{
		return [
			'signup' => ['username', 'email', 'password'],
			'resetPassword' => ['password'],
			'requestPasswordResetToken' => ['email'],
		];
	}

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			if (($this->isNewRecord || $this->getScenario() === 'resetPassword') && !empty($this->password)) {
				$this->password_hash = Security::generatePasswordHash($this->password);
			}
			if ($this->isNewRecord) {
				$this->auth_key = Security::generateRandomKey();
			}

			$dirty = $this->getDirtyAttributes(['email', 'username']);
			if ($this->isNewRecord || isset($dirty['email'])) $this->email_canonical = $this->canonicalize($this->email);
			if ($this->isNewRecord || isset($dirty['username'])) $this->username_canonical = $this->canonicalize($this->username);

			return true;
		}
		return false;
	}

	public function canonicalize($string)
	{
		return mb_convert_case($string, MB_CASE_LOWER, mb_detect_encoding($string));
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'username' => 'Username',
			'username_canonical' => 'Username Canonical',
			'auth_key' => 'Auth Key',
			'password_hash' => 'Password Hash',
			'password_reset_token' => 'Password Reset Token',
			'email' => 'Email',
			'email_canonical' => 'Email Canonical',
			'email_confirmed' => 'Email Confirmed',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'nick_name' => 'Nick Name',
			'patronimic' => 'Patronimic',
			'short_name' => 'Short Name',
			'full_name' => 'Full Name',
			'date_of_birth' => 'Date Of Birth',
			'gender' => 'Gender',
			'facebook_uid' => 'Facebook Uid',
			'facebook_name' => 'Facebook Name',
			'facebook_data' => 'Facebook Data',
			'twitter_uid' => 'Twitter Uid',
			'twitter_name' => 'Twitter Name',
			'twitter_data' => 'Twitter Data',
			'gplus_uid' => 'Gplus Uid',
			'gplus_name' => 'Gplus Name',
			'gplus_data' => 'Gplus Data',
			'vk_uid' => 'Vk Uid',
			'vk_name' => 'Vk Name',
			'vk_data' => 'Vk Data',
			'status' => 'Status',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
		];
	}
}
