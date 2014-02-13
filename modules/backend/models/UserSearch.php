<?php

namespace maddoger\user\modules\backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use maddoger\user\models\User;
use Yii;

/**
 * UserSearch represents the model behind the search form about User.
 */
class UserSearch extends Model
{
	public $id;
	public $username;
	public $email;
	public $email_confirmed = '';
	public $first_name;
	public $last_name;
	public $nick_name;
	public $patronymic;
	public $short_name;
	public $full_name;
	public $date_of_birth;
	public $gender;
	public $facebook_uid;
	public $facebook_name;
	public $facebook_data;
	public $twitter_uid;
	public $twitter_name;
	public $twitter_data;
	public $gplus_uid;
	public $gplus_name;
	public $gplus_data;
	public $vk_uid;
	public $vk_name;
	public $vk_data;
	public $status;
	public $last_visit_time;
	public $created_at;
	public $updated_at;
	public $avatar;

	public function rules()
	{
		return [
			[['id', 'last_visit_time', 'created_at', 'updated_at'], 'integer'],
			[['username', 'email', 'first_name', 'last_name', 'nick_name', 'patronymic', 'short_name', 'full_name', 'date_of_birth', 'gender', 'facebook_uid', 'facebook_name', 'facebook_data', 'twitter_uid', 'twitter_name', 'twitter_data', 'gplus_uid', 'gplus_name', 'gplus_data', 'vk_uid', 'vk_name', 'vk_data', 'status', 'avatar'], 'safe'],
			[['email_confirmed'], 'boolean'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('maddoger/user', 'ID'),
			'username' => Yii::t('maddoger/user', 'Username'),
			'username_canonical' => Yii::t('maddoger/user', 'Username Canonical'),
			'auth_key' => Yii::t('maddoger/user', 'Auth Key'),
			'password' => Yii::t('maddoger/user', 'Password'),
			'password_hash' => Yii::t('maddoger/user', 'Password Hash'),
			'password_reset_token' => Yii::t('maddoger/user', 'Password Reset Token'),
			'email' => Yii::t('maddoger/user', 'Email'),
			'email_canonical' => Yii::t('maddoger/user', 'Email Canonical'),
			'email_confirmed' => Yii::t('maddoger/user', 'Email Confirmed'),
			'first_name' => Yii::t('maddoger/user', 'First Name'),
			'last_name' => Yii::t('maddoger/user', 'Last Name'),
			'nick_name' => Yii::t('maddoger/user', 'Nick Name'),
			'patronymic' => Yii::t('maddoger/user', 'Patronymic'),
			'short_name' => Yii::t('maddoger/user', 'Short Name'),
			'full_name' => Yii::t('maddoger/user', 'Full Name'),
			'avatar' => Yii::t('maddoger/user', 'Avatar'),
			'date_of_birth' => Yii::t('maddoger/user', 'Date Of Birth'),
			'gender' => Yii::t('maddoger/user', 'Gender'),
			'facebook_uid' => Yii::t('maddoger/user', 'Facebook Uid'),
			'facebook_name' => Yii::t('maddoger/user', 'Facebook Name'),
			'facebook_data' => Yii::t('maddoger/user', 'Facebook Data'),
			'twitter_uid' => Yii::t('maddoger/user', 'Twitter Uid'),
			'twitter_name' => Yii::t('maddoger/user', 'Twitter Name'),
			'twitter_data' => Yii::t('maddoger/user', 'Twitter Data'),
			'gplus_uid' => Yii::t('maddoger/user', 'Gplus Uid'),
			'gplus_name' => Yii::t('maddoger/user', 'Gplus Name'),
			'gplus_data' => Yii::t('maddoger/user', 'Gplus Data'),
			'vk_uid' => Yii::t('maddoger/user', 'Vk Uid'),
			'vk_name' => Yii::t('maddoger/user', 'Vk Name'),
			'vk_data' => Yii::t('maddoger/user', 'Vk Data'),
			'status' => Yii::t('maddoger/user', 'Status'),
			'last_visit_time' => Yii::t('maddoger/user', 'Last visit time'),
			'created_at' => Yii::t('maddoger/user', 'Create Time'),
			'updated_at' => Yii::t('maddoger/user', 'Update Time'),
		];
	}

	public function search($params)
	{
		$query = User::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			if (count($query->orderBy) == 0) {
				$query->addOrderBy('username');
			}
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'username', true);
		$this->addCondition($query, 'email', true);
		$this->addCondition($query, 'email_confirmed');
		$this->addCondition($query, 'first_name', true);
		$this->addCondition($query, 'last_name', true);
		$this->addCondition($query, 'nick_name', true);
		$this->addCondition($query, 'patronymic', true);
		$this->addCondition($query, 'short_name', true);
		$this->addCondition($query, 'full_name', true);
		$this->addCondition($query, 'date_of_birth');
		$this->addCondition($query, 'gender', true);
		$this->addCondition($query, 'facebook_uid', true);
		$this->addCondition($query, 'facebook_name', true);
		$this->addCondition($query, 'facebook_data', true);
		$this->addCondition($query, 'twitter_uid', true);
		$this->addCondition($query, 'twitter_name', true);
		$this->addCondition($query, 'twitter_data', true);
		$this->addCondition($query, 'gplus_uid', true);
		$this->addCondition($query, 'gplus_name', true);
		$this->addCondition($query, 'gplus_data', true);
		$this->addCondition($query, 'vk_uid', true);
		$this->addCondition($query, 'vk_name', true);
		$this->addCondition($query, 'vk_data', true);
		$this->addCondition($query, 'status', true);
		$this->addCondition($query, 'last_visit_time');
		$this->addCondition($query, 'created_at');
		$this->addCondition($query, 'updated_at');
		$this->addCondition($query, 'avatar', true);
		return $dataProvider;
	}

	protected function addCondition($query, $attribute, $partialMatch = false)
	{
		$value = $this->$attribute;
		if (trim($value) === '') {
			return;
		}
		if ($partialMatch) {
			$value = strtr($value, ['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']);
			$query->andWhere(['like', $attribute, $value]);
		} else {
			$query->andWhere([$attribute => $value]);
		}
	}
}
