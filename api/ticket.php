<?php
namespace api;
class ticket
{
    public function getTicket($event_id,$user_id){
        $event = new events();
        if($user_id == ''){
            return array(400,'用户id错误');
        }
        if(in_array($event_id,$event ->getEventIds())){
            $this->newTicket($event_id,$this->getLastTicket($event_id));;
            if($this->checkTicket($event_id,$user_id)){
                return array(201,'已经抢到票了');
            } else {
                $theEvent = $event->getEvent($event_id);
                $time = time();
                if((int)$theEvent['beginTime'] > $time){
                    return array(400,"还没有开始抢票");
                } else if((int)$theEvent['endTime'] < $time){
                    return array(401,'抢票已经结束');
                } else {
                    return $this->get($event_id,$user_id);
                }
            }
        } else {
            return array(404,'这个活动不存在');
        }
    }
    public static function getLastTicket($eventId){
        $event = new events();
        $redis = redis::getInstance()->getRedis();
        $key = 'event:'.$eventId.":tickets";
        $res = $redis->exists($key);
        if(!$res){
            $last = $event->getLast($eventId);
            return $last;
        } else {
            return $redis->lLen($key);
        }

    }
    private function checkTicket($event_id,$user_id){
        $redis = redis::getInstance()->getRedis();
        $key = 'event:'.$event_id.':geted';
        if($redis -> sIsMember($key,$user_id)){
            return true;
        } else {
            $mysql = mysql::getInstance()->getDb();
            $res = $mysql->query('select * from attends where id='.(int)$user_id.' and event_id = '.$event_id);
            $r = $res->fetch_assoc();
            if($r){
                $redis->sAdd($key,$user_id);
                return true;
            } else {
                return false;
            }
        }
    }
    private function newTicket($event_id , $last){
        $redis = redis::getInstance()->getRedis();
        $key = 'event:'.$event_id.":tickets";
        if(!$redis->exists($key)){
            $redis->watch($key);
            $redis->multi();
            for($i = 1; $i <= $last; $i++ ){
                $redis->lPush($key,$key.":".$i);
            }
            $redis->exec();
        }
    }
    private function get($event_id,$user_id){
        $redis = redis::getInstance()->getRedis();
        $key = 'event:'.$event_id.":tickets";
        $keygeted = 'event:'.$event_id.':geted';
        if($redis->lLen($key) > 0){
            $redis -> multi();
            $ticket = $redis -> rPop($key);
            $redis->sAdd($keygeted,$user_id);
            $res = $redis->exec();
            $mysql = mysql::getInstance()->getDb();
            if($res[1]){
                $res2 = $mysql ->query('insert attends (user_id,event_id,uptime) value('.$user_id.','.$event_id.','.time().')');
            } else {
                $redis->lPush($key,$ticket);
            }
            return array(200,'抢票成功');
        } else {
            return array(300,'票已经抢完了');
        }
    }
}