<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "database_type".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $active
 */
class DatabaseType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'database_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active'], 'default', 'value' => null],
            [['active'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    public static function all() {
        return ArrayHelper::map(self::find()->where(['active'=>1])->all(), 'id', 'name');
    }
}
