<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\db\Query;
use yii\console\Controller;
use yii\console\ExitCode;
use phpseclib3\Net\SSH2;
use phpseclib3\Net\SFTP;
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
    public $sshHost = '185.196.213.110'; //'second-server-ip';
    public $sshUser = 'root'; //'username';
    public $sshPassword = 'borkitob@2023'; //'password';
    public $dbUser = 'postgres';
    public $dbPassword = 'borkitob@2023db';
    public $dbName = 'borkitob';
    public $exportPath = '/var/www/exported_database.sql';
    public $localFilePath = '@app/runtime/exported_database.sql';

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionBackupDb($database_id)
    {
        $database = Databases::findOne($database_id);

        if ($database) {
            $dsn = Yii::$app->db->dsn;

            $dsn1 = $dsn;
            preg_match('/host=([^;]+)/', $dsn1, $matches);
            $host = $matches[1];

            $ownFlag = true;

            if (!empty($database->host) && $host != $database->host) { // tashqi serverdan olamiz
                $ownFlag = false;
            }
            
            if ($ownFlag) {
                
                $db_type_id = $database->db_type_id;
                $db_user = $database->db_user;
                $db_host = $database->db_host;

                $db_name = $database->name;
                $db_password = $database->db_password;

                if (empty($database->host)) {

                    $dsn2 = $dsn;

                    $db_user = Yii::$app->db->username;
                    
                    preg_match('/dbname=([^;]+)/', $dsn2, $matches);
                    
                    $db_name = $matches[1];
                    $db_password = Yii::$app->db->password;

                    if (strpos($dsn, 'mysql:') === 0) {
                        $db_type_id = 2;
                    } elseif (strpos($dsn, 'pgsql:') === 0) {
                        $db_type_id = 1;
                    } else {
                        $db_type_id = '';
                    }
                }

                $result = $this->backup($db_type_id, $db_name, $db_user, $db_password, $db_host);
            } else {
                $result = $this->exportAndImport($database->host, $database->ssh_user, $database->password, $database->db_password, $database->db_user, $database->name, $database->db_type_id);
            }

            if ($result['status'] == true) {
                echo $database->name . ' dan backup olindi' . "\n";

                $backupModel = new Backups();
                $backupModel->database_id = $database->id;
                $backupModel->db_type_id = $database->db_type_id;
                $backupModel->datetime = date('Y-m-d H:i:s');
                $backupModel->url = $result['pathUrl'];

                if (file_exists($result['localFilePath'])) {
                    $fileSizeBytes = filesize($result['localFilePath']);

                    // Fayl hajmini MB ga aylantirish
                    $fileSizeMB = $fileSizeBytes / (1024 * 1024);

                    $backupModel->file_size = $fileSizeMB;

                    // echo "Fayl hajmi: " . round($fileSizeMB, 2) . " MB";
                }

                $backupModel->save(false);

                $text = $database->project_name . ' dan backup olindi. Hajmi: ' . $backupModel->file_size . ' MB (tar)';

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

        if (!is_dir($path)) { // If the directory does not exist, create it
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
                'pathUrl' => $database_name . '/' . $date . '.tar',
                'localFilePath' => $filename_tar
            ];
//            $command = 'sshpass -p "bts@202011192009#" scp -r -P 2208 '.Yii::getAlias('@app').'/'.$filename_tar.' adham@83.221.163.9:/home/adham/data';
//            echo 'command: '.$command. "\n";
//            $output = shell_exec($command);
        }

        return [
            'status' => false,
            'pathUrl' => $filename_tar,
            'localFilePath' => $filename_tar
        ];
    }

    protected function exportAndImport($sshHost, $sshUser, $sshPassword, $dbPassword, $dbUser, $dbName, $dbType)
    {
        $path = Yii::getAlias('@app/data/' . $dbName . '/');

        if (!is_dir($path)) { // If the directory does not exist, create it
            mkdir($path, 0755, true);
        }

        $date = time();

        $filename_sql = $path . $date . '.sql';
        $filename_tar = $path . $date . '.tar';

        $exportPath = '/var/www/exported_database.sql';
        $localFilePath = $filename_sql; //'@app/runtime/exported_database.sql';

        $ssh = new SSH2($sshHost);
        if (!$ssh->login($sshUser, $sshPassword)) {
            $this->stderr("Ulanish muvaffaqiyatsiz.\n");
            return;
        }

        if ($dbType == 1) { // postgressql
            // PostgreSQL baza eksporti
            $exportCommand = "PGPASSWORD='{$dbPassword}' pg_dump -U {$dbUser} -d {$dbName} > {$this->exportPath}";
            $output = $ssh->exec($exportCommand);

            // Faylni yuklab olish uchun SFTP ulanishi
            $sftp = new SFTP($sshHost);
            if (!$sftp->login($sshUser, $sshPassword)) {
                $this->stderr("SFTP ulanishi muvaffaqiyatsiz.\n");
                return;
            }

            // Faylni yuklab olish
            $localPath = \Yii::getAlias($localFilePath);
            $sftp->get($exportPath, $localPath);

            // Birinchi serverdagi PostgreSQL bazaga import qilish
            $importCommand = "PGPASSWORD='{$dbPassword}' psql -U {$dbUser} -d {$dbName} < {$localPath}";
            exec($importCommand);

            $this->stdout("PostgreSQL baza muvaffaqiyatli yuklandi va import qilindi.\n");
        } elseif ($dbType == 2) { // mysql
            // Baza eksporti
            $exportCommand = "mysqldump -u {$dbUser} -p{$dbPassword} {$dbName} > {$this->exportPath}";
            $ssh->exec($exportCommand);

            // Faylni yuklab olish uchun SFTP ulanishi
            $sftp = new SFTP($sshHost);
            if (!$sftp->login($sshUser, $sshPassword)) {
                $this->stderr("SFTP ulanishi muvaffaqiyatsiz.\n");
                return;
            }

            // Faylni yuklab olish
            $localPath = \Yii::getAlias($localFilePath);
            $sftp->get($exportPath, $localPath);

            // Birinchi serverdagi bazaga import qilish
            $importCommand = "mysql -u {$dbUser} -p{$dbPassword} {$dbName} < {$localPath}";
            exec($importCommand);

            $this->stdout("Baza muvaffaqiyatli yuklandi va import qilindi.\n");
        }

        $command = 'tar -czvf ' . $filename_tar . ' ' . $filename_sql;
        echo 'command: ' . $command . "\n";
        $output = shell_exec($command);

        if (is_file($filename_tar)) {

            return [
                'status' => true,
                'pathUrl' => $dbName . '/' . $date . '.tar',
                'localFilePath' => $filename_tar
            ];
        }

        return [
            'status' => false,
            'pathUrl' => $filename_tar,
            'localFilePath' => $filename_tar
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

    public function actionTest() {
        echo date('Y-m-d H:i:s');
    }

    public function actionRun($minutes=2, $hours=1, $day_of_month='*', $month='*', $day_of_week='*') {
        $cronExpression = "{$minutes} {$hours} {$day_of_month} {$month} {$day_of_week}";

        $cronJobCommand = "{$cronExpression} php /var/www/backups/yii cron/run";
        exec("echo '$cronJobCommand' | crontab -");
    }


    public function actionCleanup()
    {
        // Hozirgi oyni boshlanish sanasi
        $currentMonthStart = date('Y-m-01 00:00:00');

        // Har bir database_id uchun oxirgi zaxira nusxasini tanlash
        $subquery = (new Query())
            ->select(['id'])
            ->from('backups')
            ->where(['<', 'datetime', $currentMonthStart])
            ->andWhere([
                'id' => (new Query())
                    ->select(['MAX(id)'])
                    ->from('backups')
                    ->where('datetime < :currentMonthStart', [':currentMonthStart' => $currentMonthStart])
                    ->groupBy('database_id')
            ]);

        // Oxirgi zaxira nusxalari IDlarini olish
        $latestBackupIds = $subquery->column();

        // Eski zaxira nusxalarini topish va o'chirish
        $oldBackups = Backups::find()
            ->where(['<', 'datetime', $currentMonthStart])
            ->andWhere(['NOT IN', 'id', $latestBackupIds])
            ->all();

        foreach ($oldBackups as $backup) {
            // Fayl yo'llarini aniqlash
            $tarFilePath = Yii::getAlias('@app/data/' . $backup->url);
            $sqlFilePath = str_replace('.tar', '.sql', $tarFilePath);

            // .tar faylini o'chirish
            if (file_exists($tarFilePath)) {
                if (unlink($tarFilePath)) {
                    echo "Fayl o'chirildi: " . $tarFilePath . "\n";
                } else {
                    echo "Faylni o'chirishda xatolik yuz berdi: " . $tarFilePath . "\n";
                }
            } else {
                echo "Fayl topilmadi: " . $tarFilePath . "\n";
            }

            // .sql faylini o'chirish
            if (file_exists($sqlFilePath)) {
                if (unlink($sqlFilePath)) {
                    echo "Fayl o'chirildi: " . $sqlFilePath . "\n";
                } else {
                    echo "Faylni o'chirishda xatolik yuz berdi: " . $sqlFilePath . "\n";
                }
            } else {
                echo "Fayl topilmadi: " . $sqlFilePath . "\n";
            }

            // Zaxira yozuvini bazadan o'chirish
            if ($backup->delete()) {
                echo "Bazadan o'chirildi: " . $backup->id . $backup->datetime . "\n";
            } else {
                echo "Bazadan o'chirishda xatolik: " . $backup->id . $backup->datetime . "\n";
            }
        }
    }

}
