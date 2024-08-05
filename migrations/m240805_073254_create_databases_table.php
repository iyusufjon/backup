<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%databases}}`.
 */
class m240805_073254_create_databases_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%databases}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string('50'),
            'db_type_id' => $this->integer(),
            'host' => $this->string(),
            'password' => $this->string(),
            'port' => $this->string(),
            'db_host' => $this->string(),
            'db_password' => $this->string(),
            'db_port'  => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%databases}}');
    }
}
