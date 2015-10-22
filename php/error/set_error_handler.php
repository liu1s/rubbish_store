<?php
//set_error_handler('myError');


//会提到上个函数
//set_error_handler('myError1');

//register_shutdown_function(array(new a(), 'test'));

register_shutdown_function('shutdown');



//trigger_error('this is a error', E_USER_ERROR);

//var_dump('after trigger error');

throw new Exception('this is a exception');
//echo $this->a;

function myError1($errno, $errstr, $errfile, $errline)
{
    var_dump('d');
}

function shutdown1()
{
    var_dump('s');
}

function myError($errno, $errstr, $errfile, $errline)
{
    var_dump($errno, $errstr, $errfile, $errline);
}

function shutdown()
{
    $error = error_get_last();
    var_dump($error);
//    if (isset($error['type']))
//    {
//        switch ($error['type'])
//        {
//            case E_ERROR :
//            case E_PARSE :
//            case E_DEPRECATED:
//            case E_CORE_ERROR :
//            case E_COMPILE_ERROR :
//                $message = $error['message'];
//                $file = $error['file'];
//                $line = $error['line'];
//                $log = "$message ($file:$line)\nStack trace:\n";
//            echo $log;
//        }
//    }
}

class a
{
    function test(){
        var_dump('test');
    }
}