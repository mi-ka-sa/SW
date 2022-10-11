<?php

namespace sw;

class ErrorHandler
{
    public function __construct()
    {
        if (DEBUG) {
            error_reporting(-1);
        } else {
            error_reporting(0);
        }
        set_exception_handler([$this, 'exeptionHandler']);
        set_error_handler([$this, 'errorHandler']);
        ob_start();
        register_shutdown_function([$this, 'fatalErrorHandler']);
    }

    public function errorHandler($err_number, $err_string, $err_file, $err_line)
    {
        $this->logError($err_string, $err_file, $err_line);
        $this->displayError($err_number, $err_string, $err_file, $err_line);
    }

    public function fatalErrorHandler()
    {
        $error = error_get_last();
        if (!empty($error) && $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {
            $this->logError($error['message'], $error['file'], $error['line']);
            ob_end_clean();
            $this->displayError($error['type'], $error['message'], $error['file'], $error['line']);
        } else {
            ob_end_flush();
        }
    }

    public function exeptionHandler(\Throwable $e)
    {
        $this->logError($e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayError('Exeption', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }

    protected function logError($msg = '', $file = '', $line = '')
    {
        file_put_contents(LOGS . 'errors.log', 
        "[" . date('Y-m-d H-i-s') . "] Description message: {$msg} | File name: {$file} | Line in file: {$line}
                \n----------------------------\n", 
                FILE_APPEND);
    }

    protected function displayError($err_number, $err_string, $err_file, $err_line, $response = 500)
    {
        if ($response == 0) {
            $response = 404;
        }
        http_response_code($response);

        if ($response == 404 && !DEBUG) {
            require_once WWW . '/errors/404.php';
            die;
        }

        if (DEBUG) {
            require_once WWW . '/errors/development.php';
        } else {
            require_once WWW . '/errors/production.php';
        }

        die;
    }
}
