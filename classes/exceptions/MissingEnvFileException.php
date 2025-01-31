<?php

class MissingEnvFileException extends Exception {
    protected $message = 'The .env file is required but missing.';
}

?>
