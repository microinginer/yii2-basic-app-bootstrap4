<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Class BackupController
 * @package app\commands
 */
class BackupController extends Controller
{
    private $username;
    private $password;
    private $host;
    private $dbname;

    public function init()
    {
        $this->username = Yii::$app->getDb()->username;
        $this->password = Yii::$app->getDb()->password;

        $dsn = explode(';', Yii::$app->getDb()->dsn);
        $this->host = getenv('DB_HOST');

        $this->dbname = getenv('DB_NAME');
    }

    public function actionIndex()
    {

        $dir = Yii::getAlias('@app/backup/' . date('Y/m/'));
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $dir .= date('d_H_i') . "_{$this->dbname}.sql.gz";

        $this->stdout("Backing up database to {$dir}" . PHP_EOL, Console::FG_GREEN);

        exec("mysqldump --user={$this->username} --password={$this->password} --host={$this->host} {$this->dbname} | gzip > {$dir} 2>&1", $output);

        if (is_array($output)) {
            foreach ($output as $message) {
                $this->stdout($message . PHP_EOL, Console::FG_RED);
            }
        } else {
            $this->stdout($output . PHP_EOL, Console::FG_YELLOW);
        }

        if (file_exists($dir)) {
            $this->stdout('Готово, файл успешно архивирован' . PHP_EOL, Console::FG_GREEN, Console::BOLD);
            $this->actionSendMail($dir);
            return ExitCode::OK;
        }

        $this->stdout('Ошибка не получилось архивировать файл.' . PHP_EOL, Console::FG_RED, Console::BOLD);

        return ExitCode::OK;
    }

    public function actionSendMail($filename)
    {
        $message = Yii::$app->mailer->compose()
            ->setFrom([getenv('SMTP_LOGIN') => 'Database backup'])
            ->setTo([Yii::$app->params['adminEmail']])
            ->setSubject('DataBase Backup ' . date('d.m.Y'))
            ->setHtmlBody('<h1>Database backup</h1>');

        $message->attach($filename);

        if ($message->send()) {
            $this->stdout('Файл успешно отправлено' . PHP_EOL, Console::FG_GREEN, Console::BOLD);

            unlink($filename);
        }
    }
}
