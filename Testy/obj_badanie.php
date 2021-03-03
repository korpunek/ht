<?php
class badanie
{

    public $nazwa;
    public $data;
    

    public function __get($name)
    {
        return false;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }




}