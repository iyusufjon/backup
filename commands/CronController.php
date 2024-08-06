<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Databases;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CronController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionBackupDb($message = 'hello cron backup')
    {
        $databases = Databases::find()->all();

        foreach ($databases as $database) {
            $databaseType = $database->databaseType ?? '';

            $db_user = 'backup';

            $this->backup($databaseType, $database->name, $db_user, $database->db_password, $database->db_host);
        }

        return ExitCode::OK;
    }

    protected function backup($database_type, $database_name, $user, $db_password, $host='localhost') {
        $date = '';
        $filename_sql = 'data/' . $database_name . $date . '.sql';
        $filename_tar = 'data/' . $database_name . $date . '.tar';

        echo 'filename sql: ' . $filename_sql . "\n";
        echo 'filename tar: ' . $filename_tar . "\n";

        // $command = 'mysqldump -u root -p'.Yii::$app->db->password.' bts > '.$filename_sql;
        // $command = 'PGPASSWORD="' . \Yii::$app->db->password . '" pg_dump -U backup -h localhost backupdb > ' . $filename_sql;

        $command = 'PGPASSWORD="' . $db_password . '" pg_dump -U "' . $user . '" -h "' . $host . '" "' . $database_name . '" > ' . $filename_sql;


        echo 'command: ' . $command . "\n";
        $output = shell_exec($command);

        $command = 'tar -czvf ' . $filename_tar . ' ' . $filename_sql;
        echo 'command: ' . $command . "\n";
        $output = shell_exec($command);

        if (is_file($filename_tar)) {
              echo $database_name . ' dan backup olindi' . "\n";
//            $command = 'sshpass -p "bts@202011192009#" scp -r -P 2208 '.Yii::getAlias('@app').'/'.$filename_tar.' adham@83.221.163.9:/home/adham/data';
//            echo 'command: '.$command. "\n";
//            $output = shell_exec($command);
        }
    }
}
