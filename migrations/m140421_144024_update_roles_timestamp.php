<?php

use yii\db\Schema;

class m140421_144024_update_roles_timestamp extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%auth_item}}', 'created_at', Schema::TYPE_INTEGER);
        $this->addColumn('{{%auth_item}}', 'updated_at', Schema::TYPE_INTEGER);

        $this->addColumn('{{%auth_rule}}', 'created_at', Schema::TYPE_INTEGER);
        $this->addColumn('{{%auth_rule}}', 'updated_at', Schema::TYPE_INTEGER);

        $this->addColumn('{{%auth_assignment}}', 'created_at', Schema::TYPE_INTEGER);
        $this->addColumn('{{%auth_assignment}}', 'updated_at', Schema::TYPE_INTEGER);
    }

    public function safeDown()
    {
        $this->dropColumn('{{%auth_item}}', 'created_at');
        $this->dropColumn('{{%auth_item}}', 'updated_at');

        $this->dropColumn('{{%auth_rule}}', 'created_at');
        $this->dropColumn('{{%auth_rule}}', 'updated_at');

        $this->dropColumn('{{%auth_assignment}}', 'created_at');
        $this->dropColumn('{{%auth_assignment}}', 'updated_at');
    }
}
