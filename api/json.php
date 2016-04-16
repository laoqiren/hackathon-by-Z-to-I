<?php

namespace api;

class json
{
    public static function create($status,$message,$data){
        echo json_encode(array('status'=>$status,'message'=>$message,'data'=>$data));
    }
}