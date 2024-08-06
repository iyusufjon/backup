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
    public function actionBackup($message = 'hello cron backup')
    {
        $date = '';
        $filename_sql = 'data/backup_postgres' . $date . '.sql';
        $filename_tar = 'data/backup_postgres' . $date . '.tar';

        echo 'filename sql: ' . $filename_sql . "\n";
        echo 'filename tar: ' . $filename_tar . "\n";

//        $command = 'mysqldump -u root -p'.Yii::$app->db->password.' bts > '.$filename_sql;
        $command = 'PGPASSWORD="' . \Yii::$app->db->password . '" pg_dump -U backup -h localhost backupdb > ' . $filename_sql;

        echo 'command: ' . $command . "\n";
        $output = shell_exec($command);

        $command = 'tar -czvf ' . $filename_tar . ' ' . $filename_sql;
        echo 'command: ' . $command . "\n";
        $output = shell_exec($command);

        if (is_file($filename_tar)) {
//            $command = 'sshpass -p "bts@202011192009#" scp -r -P 2208 '.Yii::getAlias('@app').'/'.$filename_tar.' adham@83.221.163.9:/home/adham/data';
//            echo 'command: '.$command. "\n";
//            $output = shell_exec($command);
        }

        echo $message . "\n";

        return ExitCode::OK;
    }
}
