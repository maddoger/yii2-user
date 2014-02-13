<?php

namespace maddoger\user\components;

use yii\web\DbSession;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Connection;

class Session extends DbSession
{
	public $sessionTable = null;

	public function init()
	{
		if (is_string($this->db)) {
			$this->db = Yii::$app->getComponent($this->db);
		}
		if (!$this->db instanceof Connection) {
			throw new InvalidConfigException("DbSession::db must be either a DB connection instance or the application component ID of a DB connection.");
		}
		if (!$this->sessionTable) {
			$this->sessionTable = $this->db->tablePrefix . 'session';
		}
		parent::init();
	}
}