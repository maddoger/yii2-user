<?php

use yii\db\Schema;

class m131219_084259_create_user_table extends \yii\db\Migration
{
	public function safeUp()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}

		$this->createTable('{{%user}}', [
			'id' => Schema::TYPE_PK,

			'username' => Schema::TYPE_STRING . ' NOT NULL',
			'username_canonical' => Schema::TYPE_STRING . ' NOT NULL',

			'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
			'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
			'password_reset_token' => Schema::TYPE_STRING . '(32)',

			'email' => Schema::TYPE_STRING . ' NOT NULL',
			'email_canonical' => Schema::TYPE_STRING . ' NOT NULL',
			'email_confirmed' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE',

			//Name
			'first_name' => Schema::TYPE_STRING. '(50)',
			'last_name' => Schema::TYPE_STRING. '(50)',
			'nick_name' => Schema::TYPE_STRING. '(50)',
			'patronimic' => Schema::TYPE_STRING. '(50)',

			'short_name' => Schema::TYPE_STRING. '(100)',
			'full_name' => Schema::TYPE_STRING. '(255)',

			//Avatar
			'avatar' => Schema::TYPE_STRING. '(255)',

			//Info
			'date_of_birth' => Schema::TYPE_DATE,
			'gender' => Schema::TYPE_STRING. '(1)',

			//Social
			'facebook_uid' => Schema::TYPE_STRING. '(255)',
			'facebook_name' => Schema::TYPE_STRING. '(255)',
			'facebook_data' => Schema::TYPE_TEXT,

			'twitter_uid' => Schema::TYPE_STRING. '(255)',
			'twitter_name' => Schema::TYPE_STRING. '(255)',
			'twitter_data' => Schema::TYPE_TEXT,

			'gplus_uid' => Schema::TYPE_STRING. '(255)',
			'gplus_name' => Schema::TYPE_STRING. '(255)',
			'gplus_data' => Schema::TYPE_TEXT,

			'vk_uid' => Schema::TYPE_STRING. '(255)',
			'vk_name' => Schema::TYPE_STRING. '(255)',
			'vk_data' => Schema::TYPE_TEXT,

			'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',

			'last_visit_time' => Schema::TYPE_INTEGER,
			'create_time' => Schema::TYPE_INTEGER.' NOT NULL',
			'update_time' => Schema::TYPE_INTEGER.' NOT NULL',
		], $tableOptions);

		$this->createIndex($this->db->tablePrefix.'user_username_canonical_ux', '{{%user}}', 'username_canonical', true);
		$this->createIndex($this->db->tablePrefix.'user_email_canonical_ux', '{{%user}}', 'email_canonical', true);
	}

	public function safeDown()
	{
		$this->dropIndex($this->db->tablePrefix.'user_username_canonical_ux', '{{%user}}');
		$this->dropIndex($this->db->tablePrefix.'user_email_canonical_ux', '{{%user}}');

		$this->dropTable('{{%user}}');
	}
}
