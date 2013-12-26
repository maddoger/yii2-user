<?php

namespace rusporting\user\modules\backend\models;

/**
 * This is the model class for table "public.tbl_auth_item_child".
 *
 * @property string $parent
 * @property string $child
 *
 * @property AuthItem $parentRecord
 * @property AuthItem $childRecord
 */
class AuthItemChild extends \rusporting\core\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['parent', 'child'], 'required'],
			[['parent', 'child'], 'string', 'max' => 64]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'parent' => 'Parent',
			'child' => 'Child',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getParentRecord()
	{
		return $this->hasOne(AuthItem::className(), ['name' => 'parent']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getChildRecord()
	{
		return $this->hasOne(AuthItem::className(), ['name' => 'child']);
	}
}