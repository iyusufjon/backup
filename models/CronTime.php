<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cron_time".
 *
 * @property int $id
 * @property int|null $database_id
 * @property string $minutes Minutes (0-59, *)
 * @property string $hours Hours (0-23, *)
 * @property string $day_of_month Day of Month (1-31, *)
 * @property string $month Month (1-12, *)
 * @property string $day_of_week Day of Week (0-7, *)
 * @property int|null $active
 */
class CronTime extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cron_time';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['database_id'], 'required'],
            [['database_id', 'active'], 'default', 'value' => null],
            [['database_id', 'active'], 'integer'],
        
            [['minutes', 'hours', 'day_of_month', 'month', 'day_of_week'], 'required'],
            [['minutes', 'hours', 'day_of_month', 'month', 'day_of_week'], 'string'],
            [['minutes'], 'match', 'pattern' => '/^(\*|[0-5]?[0-9])$/', 'message' => 'Invalid minutes format'],
            [['hours'], 'match', 'pattern' => '/^(\*|([0-1]?[0-9]|2[0-3]))$/', 'message' => 'Invalid hours format'],
            [['day_of_month'], 'match', 'pattern' => '/^(\*|([0-2]?[0-9]|3[0-1]))$/', 'message' => 'Invalid day of month format'],
            [['month'], 'match', 'pattern' => '/^(\*|([0-1]?[0-9]|1[0-2]))$/', 'message' => 'Invalid month format'],
            [['day_of_week'], 'match', 'pattern' => '/^(\*|[0-6]|7)$/', 'message' => 'Invalid day of week format'],
        
            // [['minutes', 'hours', 'day_of_month', 'month', 'day_of_week'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'database_id' => Yii::t('app', 'Database ID'),
            'minutes' => Yii::t('app', 'Minutes'),
            'hours' => Yii::t('app', 'Hours'),
            'day_of_month' => Yii::t('app', 'Day Of Month'),
            'month' => Yii::t('app', 'Month'),
            'day_of_week' => Yii::t('app', 'Day Of Week'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    public function getDatabase()
    {
        return $this->hasOne(Databases::className(), ['id' => 'database_id']);
    }
}
