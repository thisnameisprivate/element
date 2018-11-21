<?php

use \GatewayWorker\Lib\Gateway;

class Events {
    /*
     *  @当客户端连接时
     * */
    public static function onConnect ($client_id) {
        Gateway::sendToClient($client_id, json_encode(array(
            'type'      => 'init',
            'client_id' => $client_id
        )));
    }
    public static function onMessage ($client_id, $message) {

    }
}