<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%databases}}`.
 */
class m240807_102802_add_project_name_column_to_databases_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%databases}}', 'project_name', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%databases}}', 'project_name');
    }
}
