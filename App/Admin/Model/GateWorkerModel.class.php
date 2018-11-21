<?php
namespace Admin\Model;
use Think\Model;
use Org\Gateway;

class GateWorkerModel extends Model{
    public function getClientId ($client_id) {
        $Gateway = new \Org\Gateway\Gateway();
        $Gateway::$registerAddress = '211.149.x.x:xxxx';
    }
}