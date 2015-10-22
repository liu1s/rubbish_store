<?php
class test1{
    public $arr = 1;
}

class test2{
    public $arr = 1;

    public function __clone()
    {
    }
}


$obj1 = new test1();
$obj2 = $obj1;
$obj1->arr = 2;
var_dump($obj2->arr);

$obj3 = new test2();
$obj4 = clone $obj3;
$obj3->arr = 2;
var_dump($obj4->arr);



