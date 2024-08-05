<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "databases".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $db_type_id
 * @property string|null $host
 * @property string|null $password
 * @property string|null $port
 * @property string|null $db_host
 * @property string|null $db_password
 * @property string|null $db_port
 */
class Databases extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'databases';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['db_type_id'], 'default', 'value' => null],
            [['db_type_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['host', 'password', 'port', 'db_host', 'db_password', 'db_port'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'db_type_id' => 'Db Type ID',
            'host' => 'Host',
            'password' => 'Password',
            'port' => 'Port',
            'db_host' => 'Db Host',
            'db_password' => 'Db Password',
            'db_port' => 'Db Port',
        ];
    }

    public static function all() {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }
}
