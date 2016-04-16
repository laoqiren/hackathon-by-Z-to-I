<?php
namespace api;
class redis
{
    private static $_redis;
    private static $_instance;
    public static function getInstance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function getRedis(){
        return self::$_redis;
    }
    private function __construct(){
        self::$_redis = new \Redis();
        self::$_redis->connect('127.0.0.1',6379);
    }

    /**
     * 对活动进行初始化
     * @param $data 活动的关联数组,id,title,beginTime,endTime,total
     */
    public function setEvent($data){
        self::$_redis->watch('event:'.$data['id']);
        self::$_redis->multi();
        if($data != array()){
            self::$_redis->hMset('event:'.$data['id'],$data);
            self::$_redis->expire('event:'.$data['id'],30);
        }
        self::$_redis->exec();
    }
    /**
     * @param $eventId 活动id
     * @return array 活动信息
     */
    public function getEvent($eventId){
        return self::$_redis->hGetAll('event:'.$eventId);
    }
}