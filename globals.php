<?php

    session_start();

    $BASE_URL = "http://" . $_SERVER["SERVER_NAME"] . dirname($_SERVER["REQUEST_URI"].'?') . '/';

class Globals {

    public static function logError(string $message): void {
        $logFile = __DIR__ . "/logs/error_log.txt"; // caminho do arquivo de log
        $date = date("Y-m-d H:i:s");                // data e hora

        // cria diretório "logs" se não existir
        if (!is_dir(dirname($logFile))) {
            mkdir(dirname($logFile), 0777, true);
        }

        // monta a linha de log
        $logEntry = "[{$date}] - {$message}\n";

        // salva no arquivo (append)
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
}
    