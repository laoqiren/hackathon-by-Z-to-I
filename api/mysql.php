<?php
namespace api;


class mysql
{
    private static $_instance = null ;
    private static $_db = null;
    public static function getInstance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    private function __construct() {
        self::$_db =  mysqli_connect('127.0.0.1', 'hackathon', 'hackathon', 'hackathon');
        self::$_db ->query('set names utf8');
    }

    /**
     * @return mysqli
     */
    public function getDb()
    {
        return self::$_db;
    }
}