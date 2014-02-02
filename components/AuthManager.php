<?php

namespace rusporting\user\components;

use yii\rbac\DbManager;
use Yii;

class AuthManager extends DbManager
{
	public $assignmentTable = null;

	/**
	 * @var string the name of the table storing authorization items. Defaults to $this->getDb()->tablePrefix.'auth_item'.
	 */
	public $itemTable = null;
	/**
	 * @var string the name of the table storing authorization item hierarchy. Defaults to $this->getDb()->tablePrefix.'auth_item_child'.
	 */
	public $itemChildTable = null;


	public function init()
	{
		if (is_string($this->db)) {
			$this->db = Yii::$app->getComponent($this->db);
		}

		if (!$this->assignmentTable) {
			$this->assignmentTable = $this->db->tablePrefix . 'auth_assignment';
		}
		if (!$this->itemTable) {
			$this->itemTable = $this->db->tablePrefix . 'auth_item';
		}
		if (!$this->itemChildTable) {
			$this->itemChildTable = $this->db->tablePrefix . 'auth_item_child';
		}
		parent::init();
	}
}