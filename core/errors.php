<?php

class ErrorHandler
{
    public static function register()
    {
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    /**
     * Interceptar errores normales (warnings, notices, etc.)
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @return mixed
     * @throws ErrorException
     */
    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    /**
     * Interceptar excepciones no capturadas
     * @param $exception
     * @return void
     */
    public static function handleException($exception)
    {
        error_log($exception);
        echo "<pre>"; print_r($exception); echo "</pre>";
        http_response_code(500);
        echo "Ha ocurrido un error interno.";
    }

    /**
     * Interceptar errores fatales (muy importante)
     * @return void
     */
    public static function handleShutdown()
    {
        $error = error_get_last();

        if ($error !== null && $error['type'] === E_ERROR) {
            error_log(print_r($error, true));
        }
    }
}

error_reporting(E_ALL);
ini_set('display_errors', 0);

ErrorHandler::register();

/* provocar notice */
// echo $hola;

/* provocar warning */
// include 'archivo_que_no_existe.php';

/* provocar excepción */
// echo 10 / 0;

/* provocar error fatal*/
// funcion_que_no_existe();