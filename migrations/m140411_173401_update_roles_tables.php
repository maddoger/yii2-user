<?php

use yii\db\Schema;

class m140411_173401_update_roles_tables extends \yii\db\Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        //https://github.com/yiisoft/yii2/blob/master/docs/guide/authorization.md

        //Rules table
        $this->createTable('{{%auth_rule}}', [
            'name' => Schema::TYPE_STRING. '(64) NOT NULL',
            'data' => Schema::TYPE_TEXT,
        ], $tableOptions);

        $this->addPrimaryKey($this->db->tablePrefix.'auth_rule_pk', '{{%auth_rule}}', 'name');

        //Links to rules
        $this->dropColumn('{{%auth_item}}', 'biz_rule');
        $this->addColumn('{{%auth_item}}', 'rule_name', Schema::TYPE_STRING. '(64)');
        $this->addForeignKey($this->db->tablePrefix.'auth_item_rule_name_fk', '{{%auth_item}}', 'rule_name', '{{%auth_rule}}', 'name', 'SET NULL', 'CASCADE');

        $this->dropColumn('{{%auth_assignment}}', 'biz_rule');
        $this->addColumn('{{%auth_assignment}}', 'rule_name', Schema::TYPE_STRING. '(64)');
        $this->addForeignKey($this->db->tablePrefix.'auth_assignment_rule_name_fk', '{{%auth_assignment}}', 'rule_name', '{{%auth_rule}}', 'name', 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey($this->db->tablePrefix.'auth_assignment_rule_name_fk', '{{%auth_assignment}}');
        $this->dropColumn('{{%auth_assignment}}', 'rule_name');
        $this->addColumn('{{%auth_assignment}}', 'biz_rule', Schema::TYPE_TEXT);

        $this->dropForeignKey($this->db->tablePrefix.'auth_item_rule_name_fk', '{{%auth_item}}');
        $this->dropColumn('{{%auth_item}}', 'rule_name');
        $this->addColumn('{{%auth_item}}', 'biz_rule', Schema::TYPE_TEXT);


        $this->dropPrimaryKey($this->db->tablePrefix.'auth_rule_pk', '{{%auth_rule}}');
        $this->dropTable('{{%auth_rule}}');
    }
}
