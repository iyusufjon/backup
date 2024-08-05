<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%backups}}`.
 */
class m240805_083621_create_backups_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%backups}}', [
            'id' => $this->primaryKey(),
            'database_id' => $this->integer(),
            'db_type_id' => $this->integer(),
            'url' => $this->string(),
            'datetime' => $this->datetime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%backups}}');
    }
}
