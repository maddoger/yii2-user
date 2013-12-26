<?php

use yii\db\Schema;

class m131219_115513_create_role_table extends \yii\db\Migration
{
	public function safeUp()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}

		//https://github.com/yiisoft/yii2/blob/master/docs/guide/authorization.md

		//Roles table
		$this->createTable('{{%auth_item}}', [
			'name' => Schema::TYPE_STRING. '(64) NOT NULL',
			'module' => Schema::TYPE_STRING. '(64)',
			'type' => Schema::TYPE_INTEGER.' NOT NULL',
			'description' => Schema::TYPE_TEXT,
			'biz_rule' => Schema::TYPE_TEXT,
			'data' => Schema::TYPE_TEXT,
		], $tableOptions);

		$this->addPrimaryKey($this->db->tablePrefix.'auth_item_pk', '{{%auth_item}}', 'name');
		$this->createIndex($this->db->tablePrefix.'auth_item_type_ix', '{{%auth_item}}', 'type');

		//Role hierarchy
		$this->createTable('{{%auth_item_child}}', [
			'parent' => Schema::TYPE_STRING. '(64) NOT NULL',
			'child' => Schema::TYPE_STRING. '(64) NOT NULL',
		], $tableOptions);

		$this->addPrimaryKey($this->db->tablePrefix.'auth_item_child_pk', '{{%auth_item_child}}', 'parent,child');
		$this->addForeignKey($this->db->tablePrefix.'auth_item_child_parent_fk', '{{%auth_item_child}}', 'parent',
			'{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');
		$this->addForeignKey($this->db->tablePrefix.'auth_item_child_child_fk', '{{%auth_item_child}}', 'child',
			'{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');


		//User - role
		$this->createTable('{{%auth_assignment}}', [
			'item_name' => Schema::TYPE_STRING. '(64) NOT NULL',
			'user_id' => Schema::TYPE_INTEGER. ' NOT NULL',
			'biz_rule' => Schema::TYPE_TEXT,
			'data' => Schema::TYPE_TEXT,
		], $tableOptions);

		$this->addPrimaryKey($this->db->tablePrefix.'auth_assignment_pk', '{{%auth_assignment}}', 'item_name,user_id');
		$this->addForeignKey($this->db->tablePrefix.'auth_assignment_item_name_fk', '{{%auth_assignment}}', 'item_name',
			'{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');
		$this->addForeignKey($this->db->tablePrefix.'auth_assignment_user_id_fk', '{{%auth_assignment}}', 'user_id',
			'{{%user}}', 'id', 'CASCADE', 'CASCADE');
	}

	public function safeDown()
	{
		$this->dropForeignKey($this->db->tablePrefix.'auth_item_child_parent_fk', '{{%auth_item_child}}');
		$this->dropForeignKey($this->db->tablePrefix.'auth_item_child_child_fk', '{{%auth_item_child}}');

		$this->dropForeignKey($this->db->tablePrefix.'auth_assignment_item_name_fk', '{{%auth_assignment}}');
		$this->dropForeignKey($this->db->tablePrefix.'auth_assignment_user_id_fk', '{{%auth_assignment}}');

		//User - role
		$this->dropPrimaryKey($this->db->tablePrefix.'auth_assignment_pk', '{{%auth_assignment}}');
		$this->dropTable('{{%auth_assignment}}');

		//Role hierarchy
		$this->dropPrimaryKey($this->db->tablePrefix.'auth_item_child_pk', '{{%auth_item_child}}');
		$this->dropTable('{{%auth_item_child}}');
		$this->dropIndex($this->db->tablePrefix.'auth_item_type_ix', '{{%auth_item}}');

		//Roles table
		$this->dropPrimaryKey($this->db->tablePrefix.'auth_item_pk', '{{%auth_item}}');
		$this->dropTable('{{%auth_item}}');
	}
}
