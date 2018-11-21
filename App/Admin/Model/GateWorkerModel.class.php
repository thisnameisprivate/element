<?php

namespace Admin\Model;
use Think\Model;
use GatewayWorker\Lib\Gateway;

class GateWorkerModel extends Model {
    public function getClientId ($client_id) {
        Gateway::$registerAddress = '211.149.x.x:1238';
    }
}