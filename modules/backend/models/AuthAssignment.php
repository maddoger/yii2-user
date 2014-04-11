<?php

namespace maddoger\user\modules\backend\models;

use maddoger\user\models\User;

/**
 * This is the model class for table "public.tbl_auth_assignment".
 *
 * @property string $item_name
 * @property integer $user_id
 * @property string $rule_name
 * @property string $data
 *
 * @property AuthItem $itemName
 * @property User $user
 */
class AuthAssignment extends \maddoger\core\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['item_name', 'user_id'], 'required'],
			[['user_id'], 'integer'],
			[['rule_name', 'data'], 'string'],
			[['item_name'], 'string', 'max' => 64],
            ['rule_name', 'filter', 'filter' => function ($value) {
                    // here we are removing all swear words from text
                    return (!$value || empty($value)) ? null : $value;
                }],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'item_name' => 'AuthItem Name',
			'user_id' => 'User ID',
			'rule_name' => 'Rule name',
			'data' => 'Data',
		];
	}

	public static function findByUserId($userId)
	{
		return static::findAll(['user_id' => $userId]);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getItemName()
	{
		return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
}
