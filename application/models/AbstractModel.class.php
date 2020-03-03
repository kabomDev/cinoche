<?php
class AbstractModel
{
    public function __construct()
    {
        $this->database = new Database;
    }
}