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
use app\models\Backups;

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

            $result = $this->backup($databaseType, $database->name, $db_user, $database->db_password, $database->db_host);

            if ($result['status'] == true) {
                echo $database_name . ' dan backup olindi' . "\n";

                $backupModel = new Backups();
                $backupModel->database_id = $database->id;
                $backupModel->db_type_id = $database->db_type_id;
                $backupModel->datetime = date('Y-m-d H:i:s');
                $backupModel->url = $result['pathUrl'];

                $backupModel->save(false);
            }
        }

        return ExitCode::OK;
    }

    protected function backup($database_type, $database_name, $user, $db_password, $host='localhost') {
        $path = Yii::getAlias('@app/data/' . $database_name . '/');

        if (!is_dir($path)) {
            // If the directory does not exist, create it
            mkdir($path, 0755, true);
        }

        $date = time();

        // $filename_sql = 'data/bts_postgres' . $date . '.sql';

        $filename_sql = $path . $date . '.sql';
        $filename_tar = $path . $date . '.tar';

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

            return [
                'status' => true,
                'pathUrl' => $filename_tar
            ];
//            $command = 'sshpass -p "bts@202011192009#" scp -r -P 2208 '.Yii::getAlias('@app').'/'.$filename_tar.' adham@83.221.163.9:/home/adham/data';
//            echo 'command: '.$command. "\n";
//            $output = shell_exec($command);
        }

        return [
            'status' => false,
            'pathUrl' => $filename_tar
        ];
    }
}
