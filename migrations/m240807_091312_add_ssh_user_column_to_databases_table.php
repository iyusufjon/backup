<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%databases}}`.
 */
class m240807_091312_add_ssh_user_column_to_databases_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%databases}}', 'ssh_user', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%databases}}', 'ssh_user');
    }
}
