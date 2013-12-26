<?php

use yii\db\Schema;

class m131225_232443_create_session_table extends \yii\db\Migration
{
	public function safeUp()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}

		$this->createTable('{{%session}}', [
			'id' => Schema::TYPE_STRING. '(40) NOT NULL',
			'expire' => Schema::TYPE_INTEGER,
			'data' => Schema::TYPE_BINARY,
		], $tableOptions);
		$this->addPrimaryKey($this->db->tablePrefix.'session_pk', '{{%session}}', 'id');
	}

	public function safeDown()
	{
		$this->dropPrimaryKey($this->db->tablePrefix.'session_pk', '{{%session}}');
		$this->dropTable('{{%session}}');

	}
}
