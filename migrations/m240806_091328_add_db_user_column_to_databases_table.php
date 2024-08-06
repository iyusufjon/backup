<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%databases}}`.
 */
class m240806_091328_add_db_user_column_to_databases_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%databases}}', 'db_user', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%databases}}', 'db_user');
    }
}
