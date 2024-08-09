<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%backups}}`.
 */
class m240807_100902_add_file_size_column_to_backups_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%backups}}', 'file_size', $this->decimal(20,4));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%backups}}', 'file_size');
    }
}
