<?php

namespace maddoger\user\modules\backend\models;

use yii\rbac\Item;
use Yii;
use maddoger\user\models\User;
use maddoger\core\ActiveRecord;

/**
 * This is the model class for table "public.tbl_auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $data
 *
 * @property AuthItemChild $children
 * @property AuthItemChild $parents
 * @property AuthAssignment $assignment
 * @property AuthItem[] $users
 */
class AuthItem extends ActiveRecord
{
	const TYPE_OPERATION = Item::TYPE_OPERATION;
	const TYPE_TASK = Item::TYPE_TASK;
	const TYPE_ROLE = Item::TYPE_ROLE;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'type'], 'required'],
			['name', 'match', 'pattern' => '/^[\da-zA-Z\.\-_]+$/', 'message' => Yii::t('maddoger/user', 'Only letters, numbers and comma are acceptable.')],
			[['type'], 'integer'],
			[['description', 'data', 'biz_rule'], 'string'],
			[['name'], 'string', 'max' => 64]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' =>  Yii::t('maddoger/user', 'Role name'),
			'type' =>  Yii::t('maddoger/user', 'Type'),
			'description' => Yii::t('maddoger/user', 'Description'),
			'biz_rule' => Yii::t('maddoger/user', 'Biz rule'),
			'data' => Yii::t('maddoger/user', 'Data'),
			'children' => Yii::t('maddoger/user', 'Children'),
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getChildren()
	{
		return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable(AuthItemChild::tableName(), ['parent' => 'name']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getParents()
	{
		return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable(AuthItemChild::tableName(), ['child' => 'name']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getAssignment()
	{
		return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getUsers()
	{
		return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable(User::tableName(), ['item_name' => 'name']);
	}

	public static function getTypeValues()
	{
		return [
			Item::TYPE_OPERATION => Yii::t('maddoger/user', 'Operation'),
			Item::TYPE_TASK => Yii::t('maddoger/user', 'Task'),
			Item::TYPE_ROLE => Yii::t('maddoger/user', 'Role')
		];
	}
}
