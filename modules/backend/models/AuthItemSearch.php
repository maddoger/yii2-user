<?php

namespace rusporting\user\modules\backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use rusporting\user\modules\backend\models\AuthItem;
use yii\rbac\Item;

/**
 * AuthItemSearch represents the model behind the search form about AuthItem.
 */
class AuthItemSearch extends Model
{
	public $name;
	public $type = Item::TYPE_ROLE;
	public $description;
	public $biz_rule;
	public $data;

	public function rules()
	{
		return [
			[['name', 'description', 'data', 'biz_rule'], 'safe'],
			[['type'], 'integer'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => 'Name',
			'type' => 'Type',
			'description' => 'Description',
			'biz_rule' => 'Biz rule',
			'data' => 'Data',
		];
	}

	public function search($params)
	{
		$query = AuthItem::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		$query->orderBy('name');

		if (!($this->load($params) && $this->validate())) {
			$this->addCondition($query, 'type');
			return $dataProvider;
		}

		$this->addCondition($query, 'name', true);
		$this->addCondition($query, 'type');
		$this->addCondition($query, 'description', true);
		$this->addCondition($query, 'biz_rule', true);
		$this->addCondition($query, 'data', true);
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
