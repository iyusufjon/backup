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
            [['project_name', 'name', 'db_type_id'], 'required'],
            [['db_type_id'], 'default', 'value' => null],
            [['db_type_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['project_name', 'host', 'ssh_user', 'password', 'port', 'db_host', 'db_password', 'db_port', 'db_user'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        // $sshHost, $sshUser, $sshPassword, $dbPassword, $dbUser, $dbName, $dbType
        return [
            'id' => 'ID',
            'name' => 'Database Name',
            'db_type_id' => 'Db Type',
            'host' => 'sshHost',
            'ssh_user' => 'sshUser',
            'password' => 'sshPassword',
            'port' => 'sshPort',
            'db_host' => 'Db Host',
            'db_port' => 'Db Port',
            'db_user' => 'Db User',
            'db_password' => 'Db Password',
        ];
    }

    public static function all() {
        return ArrayHelper::map(self::find()->all(), 'id', 'project_name');
    }

    public function getDatabaseType()
    {
        return $this->hasOne(DatabaseType::className(), ['id' => 'db_type_id']);
    }
}
