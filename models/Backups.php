<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "backups".
 *
 * @property int $id
 * @property int|null $database_id
 * @property int|null $db_type_id
 * @property string|null $url
 * @property string|null $datetime
 */
class Backups extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'backups';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['database_id', 'db_type_id'], 'default', 'value' => null],
            [['database_id', 'db_type_id'], 'integer'],
            [['datetime'], 'safe'],
            [['url'], 'string', 'max' => 255],
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
            'db_type_id' => Yii::t('app', 'Db Type ID'),
            'url' => Yii::t('app', 'Url'),
            'datetime' => Yii::t('app', 'Datetime'),
        ];
    }

    public function getDatabase()
    {
        return $this->hasOne(Databases::className(), ['id' => 'database_id']);
    }

    public function getDatabaseType()
    {
        return $this->hasOne(DatabaseType::className(), ['id' => 'db_type_id']);
    }
}
