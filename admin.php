<?php
    define('BASEDIR',__DIR__);
    function load($class){
        $class = str_replace('\\','/',$class);
        require_once BASEDIR.'/'.$class.'.php';
    }
    spl_autoload_register('load');
    $event = new \api\events();
    $eventIds = $event->getEventIds();
    $b = microtime();
    foreach ($eventIds as $e){
        $events[]=$event->getEvent($e);
    }
    $e = microtime();
    echo $e - $b;
    var_dump($events);
