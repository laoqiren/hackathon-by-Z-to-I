<?php
    define('BASEDIR',__DIR__);
    function load($class){
        $class = str_replace('\\','/',$class);
        require_once BASEDIR.'/'.$class.'.php';
    }
    spl_autoload_register('load');
    header('Content-type:application/json;charset=utf-8');
    $action = isset($_GET['action'])?$_GET['action']:'';
    switch ($action) {
        case 'eventlist':
            $events = new api\events();
            $data = $events->getEvents(isset($_GET['page'])?(int)$_GET['page']:1);
            if($data==array()){
                echo api\json::create(201,'成功但是没有数据',array());
            } else {
                echo api\json::create(200,'成功',$data);
            }
            break;
        case 'event':
            $events = new api\events();
            $eventId = isset($_GET['eventId'])?$_GET['eventId']:0;
            if($eventId == 0){
                echo api\json::create(400,'eventId参数错误',array());
            } else {
                $data  = $events->getEvent($eventId);
                if($data == null){
                    echo api\json::create(201,'成功，但是没有数据',$data);
                }else{
                    echo api\json::create(200,'成功',$data);
                }
            }
            break;
        case 'ticket':
            $ticket = new api\ticket();
            $eventId = isset($_GET['eventId'])?(int)$_GET['eventId']:0;
            $userId = isset($_GET['userId'])?$_GET['userId']:'';
            list($status,$message) = $ticket->getTicket($eventId,$userId);
            echo api\json::create($status,$message,array());
            break;
        case 'time':
            echo api\json::create(200,'成功',time());
            break;
        default:
            break;
    }
