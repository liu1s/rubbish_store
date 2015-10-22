<?php
/**
  测试相关获取类名或者属性的功能
*/


class a{
    public static $a = 1;

    public function printName1()
    {
        return __CLASS__;
    }

    public function printName2()
    {
        return get_called_class();
    }

    public function printMethod()
    {
        return __METHOD__;
    }

    public static function printA()
    {
        return self::$a;
    }

    public static function delayPrintA()
    {
        return static::$a;
    }
}

class b extends a {
    public static $a = 2;
}

$b = new b();

echo $b->printName1() . PHP_EOL;

echo $b->printName2() . PHP_EOL;

echo $b->printMethod() . PHP_EOL;

echo b::printA() . PHP_EOL;

echo b::delayPrintA() . PHP_EOL;
