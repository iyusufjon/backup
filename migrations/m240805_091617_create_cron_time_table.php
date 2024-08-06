<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cron_time}}`.
 */
class m240805_091617_create_cron_time_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cron_time}}', [
            'id' => $this->primaryKey(),
            'database_id' => $this->integer(),
            'minutes' => $this->string()->notNull()->defaultValue('*')->comment('Minutes (0-59, *)'),
            'hours' => $this->string()->notNull()->defaultValue('*')->comment('Hours (0-23, *)'),
            'day_of_month' => $this->string()->notNull()->defaultValue('*')->comment('Day of Month (1-31, *)'),
            'month' => $this->string()->notNull()->defaultValue('*')->comment('Month (1-12, *)'),
            'day_of_week' => $this->string()->notNull()->defaultValue('*')->comment('Day of Week (0-7, *)'),
            'active' => $this->integer()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cron_time}}');
    }
}
