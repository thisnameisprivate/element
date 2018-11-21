<?php

namespace Admin\Controller;
use Think\Controller;

class GateWorkerController extends Controller {
    public function worker ($client_id) {
        D('GateWorker')->getClientId($client_id);
    }
}