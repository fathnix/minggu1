<?php
namespace Fatih\Data;

class People{
    public $name ;
    public $age;
    public function __construct() {
        echo "Masukkan username anda : ";
        $this->name = trim(fgets(STDIN));
        echo "Masukkan Umur Anda : ";
        $this->age = trim(fgets(STDIN));
    }

    public function sayHello() {
        return "Hello, my name is {$this->name} and I am {$this->age} years old." . PHP_EOL;
    }
}


