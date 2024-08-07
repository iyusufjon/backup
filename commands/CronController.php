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
use app\models\CronTime;

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
    public function actionBackupDb($database_id)
    {
        $database = Databases::findOne($database_id);

        if ($database) {
            $result = $this->backup($database->db_type_id, $database->name, $database->db_user, $database->db_password, $database->db_host);

            if ($result['status'] == true) {
                echo $database->name . ' dan backup olindi' . "\n";

                $text = $database->name . ' dan backup olindi';
                
                $backupModel = new Backups();
                $backupModel->database_id = $database->id;
                $backupModel->db_type_id = $database->db_type_id;
                $backupModel->datetime = date('Y-m-d H:i:s');
                $backupModel->url = $result['pathUrl'];

                $backupModel->save(false);

                $client = new \yii\httpclient\Client();

                $response = $client->createRequest()
                    ->setMethod('POST')
                    ->setUrl('https://api.telegram.org/bot6174619802:AAH6o8ruq6muoeIJbVBrBz9SBEYXbFBz5Ag/sendMessage')
                    ->setData(['text' => json_encode($text), 'chat_id' => -1002174643856])
                    ->setOptions([
                        //'proxy' => 'tcp://proxy.example.com:5100', // use a Proxy
                        'timeout' => 2, // set timeout to 5 seconds for the case server is not responding
                    ])
                    ->send();
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

        if ($database_type == 1) { // postgresql
            $command = 'PGPASSWORD="' . $db_password . '" pg_dump -U "' . $user . '" -h "' . $host . '" "' . $database_name . '" > ' . $filename_sql;
        } elseif ($database_id == 2) { // mysql
            $command = 'mysqldump -u "' . $user . '" -p"' . $db_password . '" "' . $database_name . '" > "' . $filename_sql;
        } else {
            $command = '';
        }
        
        echo 'command: ' . $command . "\n";
        $output = shell_exec($command);

        $command = 'tar -czvf ' . $filename_tar . ' ' . $filename_sql;
        echo 'command: ' . $command . "\n";
        $output = shell_exec($command);

        if (is_file($filename_tar)) {

            return [
                'status' => true,
                'pathUrl' => $database_name . '/' . $date . '.tar'
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

    public function actionList() {
        // Barcha faollashtirilgan cron joblarni bazadan oling
        $cronJobs = CronTime::find()->where(['active' => 1])->all();

        
        $cronFileContent = "*/1 * * * * php /var/www/backup/yii cron/list\n";

        // Har bir cron job uchun crontab buyruqni yarating
        foreach ($cronJobs as $job) {
            $cronExpression = "{$job->minutes} {$job->hours} {$job->day_of_month} {$job->month} {$job->day_of_week}";
            $cronJobCommand = "$cronExpression php /var/www/backup/yii cron/backup-db " . $job->database_id;
            $cronFileContent .= "$cronJobCommand\n";
        }

        $cronFilePath = Yii::getAlias('@runtime/my_crontab');
        file_put_contents($cronFilePath, $cronFileContent);
        exec("crontab $cronFilePath");

        // Yangi crontab faylini yozing
        
        // file_put_contents('/tmp/my_crontab', $cronFileContent);

        // Yangi crontab faylini o'rnatish
        // exec('crontab /tmp/my_crontab');
    }

    public function actionRun($minutes=2, $hours=1, $day_of_month='*', $month='*', $day_of_week='*') {
        $cronExpression = "{$minutes} {$hours} {$day_of_month} {$month} {$day_of_week}";

        $cronJobCommand = "{$cronExpression} php /var/www/backups/yii cron/run";
        exec("echo '$cronJobCommand' | crontab -");
    }
}
