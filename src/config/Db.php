<?php


class Db
{

    private $dbHost         = "localhost";
    private $dbUser         = "root";
    private $dbPass         = "LocalDb2018";
    private $dbName         = "course_coupons";
    private $charSet        = "utf8";


    public function connect(){

        $mysql_connection = "mysql:host=$this->dbHost;dbname=$this->dbName;charset=$this->charSet";
        $connection = new PDO($mysql_connection,$this->dbUser,$this->dbPass);
        $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); //

        return $connection;

    }

}