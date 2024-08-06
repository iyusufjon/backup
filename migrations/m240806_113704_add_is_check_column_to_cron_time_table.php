<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%cron_time}}`.
 */
class m240806_113704_add_is_check_column_to_cron_time_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%cron_time}}', 'is_check', $this->integer()->defaultvalue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%cron_time}}', 'is_check');
    }
}
