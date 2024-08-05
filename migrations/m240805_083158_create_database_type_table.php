<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%database_type}}`.
 */
class m240805_083158_create_database_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%database_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'active' => $this->integer()->defaultValue(1),
        ]);

        Yii::$app->db->createCommand()->batchInsert('database_type', ['id','name','active'], [
            ['1','postgresql', 1],
            ['2','mysql', 0],
        ])->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%database_type}}');
    }
}
