<?php

namespace rusporting\user\models;

use rusporting\user\modules\backend\models\AuthAssignment;
use rusporting\user\modules\backend\models\AuthItemChild;
use Yii;
use rusporting\core\ActiveRecord;
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
 * @property string $patronymic
 * @property string $short_name
 * @property string $full_name
 * @property string $avatar
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
 * @property integer $last_visit_time
 * @property integer $create_time
 * @property integer $update_time
 */
class User extends ActiveRecord implements IdentityInterface
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
					ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
					ActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
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
	 * Update last visit time
	 *
	 * @param null|integer $time
	 * @return $this
	 */
	public function updateLastVisitTime($time=null)
	{
		$this->last_visit_time = $time ? $time : time();
		$this->save(false, ['last_visit_time']);
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
	 * @return array gender items list
	 */
	public static function getGenderItems()
	{
		return [
			0 => Yii::t('rusporting/user', 'Not known'),
			1 => Yii::t('rusporting/user', 'Male'),
			2 => Yii::t('rusporting/user', 'Female'),
		];
	}

	public function getRolesAssignment()
	{
		return AuthAssignment::find()->where(['user_id' => $this->id])->all();
	}

	public function getRolesNames()
	{
		$assignment = $this->getRolesAssignment();
		$names = [];
		foreach ($assignment as $model) {
			$names[] = $model->item_name;
		}
		return $names;
	}

	public function setRolesNames($roles)
	{
		AuthAssignment::find(['user_id' => $this->id])->delete();
		if ($roles) {
			foreach ($roles as $role)	{
				$model = new AuthAssignment();
				if (is_array($role)) {
					$model->setAttributes($role);
				} else {
					$model->item_name = $role;
				}
				$model->user_id = $this->id;
				$model->save(false);
			}
		}
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
			['email', 'unique', 'message' => Yii::t('rusporting/user', 'This email address has already been taken.'), 'on' => 'signup'],
			['email', 'exist', 'message' => Yii::t('rusporting/user', 'There is no user with such email.'), 'on' => 'requestPasswordResetToken'],

			['password', 'required'],
			['password', 'string', 'min' => 4],

			['avatar', 'image', 'minWidth' => 64, 'minHeight' => 64, 'maxWidth' => 150, 'maxHeight' => 150]
		];
	}

	public function scenarios()
	{
		return [
			'signup' => ['username', 'email', 'password'],
			'resetPassword' => ['password'],
			'requestPasswordResetToken' => ['email'],
			'backendCreate' => [
				'!id', 'username', 'email', 'password', 'first_name', 'last_name', 'nick_name', 'patronymic',
				'date_of_birth', 'gender', 'facebook_uid', 'facebook_name', 'facebook_data',
				'twitter_uid', 'twitter_name', 'twitter_data',
				'gplus_uid', 'gplus_name', 'gplus_data',
				'vk_uid', 'vk_name', 'vk_data',
				'status', 'avatar', 'email_confirmed',
				'rolesNames'
			],
			'backendEdit' => [
				'!id', 'username', 'email', 'first_name', 'last_name', 'nick_name', 'patronymic',
				'date_of_birth', 'gender', 'facebook_uid', 'facebook_name', 'facebook_data',
				'twitter_uid', 'twitter_name', 'twitter_data',
				'gplus_uid', 'gplus_name', 'gplus_data',
				'vk_uid', 'vk_name', 'vk_data',
				'status', 'avatar', 'email_confirmed',
				'rolesNames'
			]
		];
	}

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			if (
				$this->isNewRecord ||
				(
					$this->getScenario() === 'resetPassword' && !empty($this->password) ||
					$this->getScenario() === 'backendEdit' && !empty($this->password)
				)
			) {
				$this->password_hash = Security::generatePasswordHash($this->password);
			}
			if ($this->isNewRecord) {
				$this->auth_key = Security::generateRandomKey();
			}

			$dirty = $this->getDirtyAttributes(['email', 'username']);
			if ($this->isNewRecord || isset($dirty['email'])) $this->email_canonical = $this->canonicalize($this->email);
			if ($this->isNewRecord || isset($dirty['username'])) $this->username_canonical = $this->canonicalize($this->username);

			//Name update
			$dirtyNames = $this->getDirtyAttributes(['first_name', 'last_name', 'nick_name', 'patronymic']);
			if (count($dirtyNames)>0) {
				if (!empty($this->first_name) && !empty($this->last_name)) {
					$this->short_name = trim($this->first_name).' '.trim($this->last_name);
					$this->full_name = trim($this->last_name).' '.trim($this->first_name);
					if (!empty($this->patronymic)) $this->full_name = $this->full_name .' '.trim($this->patronymic);
				} elseif (!empty($this->nick_name)) {
					$this->short_name = $this->nick_name;
					$this->full_name = $this->nick_name;
				} else {
					$this->short_name = $this->username;
					$this->full_name = $this->username;
				}
			}

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
			'id' => Yii::t('rusporting/user', 'ID'),
			'username' => Yii::t('rusporting/user', 'Username'),
			'username_canonical' => Yii::t('rusporting/user', 'Username Canonical'),
			'auth_key' => Yii::t('rusporting/user', 'Auth Key'),
			'password' => Yii::t('rusporting/user', 'Password'),
			'password_hash' => Yii::t('rusporting/user', 'Password Hash'),
			'password_reset_token' => Yii::t('rusporting/user', 'Password Reset Token'),
			'email' => Yii::t('rusporting/user', 'Email'),
			'email_canonical' => Yii::t('rusporting/user', 'Email Canonical'),
			'email_confirmed' => Yii::t('rusporting/user', 'Email Confirmed'),
			'first_name' => Yii::t('rusporting/user', 'First Name'),
			'last_name' => Yii::t('rusporting/user', 'Last Name'),
			'nick_name' => Yii::t('rusporting/user', 'Nick Name'),
			'patronymic' => Yii::t('rusporting/user', 'Patronymic'),
			'short_name' => Yii::t('rusporting/user', 'Short Name'),
			'full_name' => Yii::t('rusporting/user', 'Full Name'),
			'avatar' => Yii::t('rusporting/user', 'Avatar'),
			'date_of_birth' => Yii::t('rusporting/user', 'Date Of Birth'),
			'gender' => Yii::t('rusporting/user', 'Gender'),
			'facebook_uid' => Yii::t('rusporting/user', 'Facebook Uid'),
			'facebook_name' => Yii::t('rusporting/user', 'Facebook Name'),
			'facebook_data' => Yii::t('rusporting/user', 'Facebook Data'),
			'twitter_uid' => Yii::t('rusporting/user', 'Twitter Uid'),
			'twitter_name' => Yii::t('rusporting/user', 'Twitter Name'),
			'twitter_data' => Yii::t('rusporting/user', 'Twitter Data'),
			'gplus_uid' => Yii::t('rusporting/user', 'Gplus Uid'),
			'gplus_name' => Yii::t('rusporting/user', 'Gplus Name'),
			'gplus_data' => Yii::t('rusporting/user', 'Gplus Data'),
			'vk_uid' => Yii::t('rusporting/user', 'Vk Uid'),
			'vk_name' => Yii::t('rusporting/user', 'Vk Name'),
			'vk_data' => Yii::t('rusporting/user', 'Vk Data'),
			'status' => Yii::t('rusporting/user', 'Status'),
			'last_visit_time' => Yii::t('rusporting/user', 'Last visit time'),
			'create_time' => Yii::t('rusporting/user', 'Create Time'),
			'update_time' => Yii::t('rusporting/user', 'Update Time'),
		];
	}
}
