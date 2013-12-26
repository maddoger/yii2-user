<?php

namespace rusporting\user\modules\backend\models;

use yii\rbac\Item;
use Yii;
use rusporting\user\models\User;

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
class AuthItem extends \rusporting\core\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'type'], 'required'],
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
			'name' =>  Yii::t('rusporting/user', 'Role name'),
			'type' =>  Yii::t('rusporting/user', 'Type'),
			'description' => Yii::t('rusporting/user', 'Description'),
			'biz_rule' => Yii::t('rusporting/user', 'Biz rule'),
			'data' => Yii::t('rusporting/user', 'Data'),
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
			Item::TYPE_OPERATION => Yii::t('rusporting/user', 'Operation'),
			Item::TYPE_TASK => Yii::t('rusporting/user', 'Task'),
			Item::TYPE_ROLE => Yii::t('rusporting/user', 'Role')
		];
	}
}
