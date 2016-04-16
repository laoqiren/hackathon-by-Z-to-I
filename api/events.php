<?php
namespace api;


class events
{
    private static $_db = null;
    public function getEvents($page){
        $page = (int)$page;
        if($page <1){
            $page = 1;
        }
        $begin = ($page - 1) * 5;
        $end = 5;
        $sql = 'select * from events ORDER BY id desc limit '.$begin.','.$end;
        $res = self::$_db ->query($sql);
        $events = array();
        while($row = $res ->fetch_assoc()){
            $events[]=$row;
        }
        return $events;
    }
    public function addEvent($data){
        $sql = 'insert events (`title`,`beginTime`,`endTime`,`total`) VALUES (?,?,?,?)';
        $stmt = self::$_db->prepare($sql);
        $stmt ->bind_param('siii',$data['title'],$data['beginTime'],$data['endTime'],$data['total']);
        $stmt ->execute();
        if($stmt ->affected_rows === 0){
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $eventId
     * @return mixed
     */
    public function getEvent($eventId){
        if($res = redis::getInstance()->getEvent($eventId) == array()){
            $eventId = (int) $eventId;
            $sql = 'select * from events WHERE id = '.$eventId;
            $res = self::$_db -> query($sql);
            $res = $res ->fetch_assoc();
            redis::getInstance()->setEvent($res);
        };
        return $res;

    }
    public function __construct(){
        self::$_db = mysql::getInstance()->getDb();
    }
}