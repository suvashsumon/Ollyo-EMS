<?php
require_once __DIR__ . '/../classes/exceptions/MissingEnvFileException.php';

class EnvLoader {
    public static function load($file = __DIR__ . '/../.env') {
        if (!file_exists($file)) {
            throw new MissingEnvFileException();
        }
        
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if(strpos(trim($line), '#') === 0) {
                continue;
            }
            
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}
?>
