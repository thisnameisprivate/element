<?php
use Workerman\Worker;
use Workerman\Lib\Timer;

require_once __DIR__ . '/Autoloader.php';
Worker::$logFile = './workerman.log';
Worker::$daemonize = true; // 以守护进程方式启动

$ws_worker = new Worker('websocket://0.0.0.0:2000');


$ws_worker->count = 4;
$ws_worker->name = 'workerServiceXMT';
$ws_worker->reloadable = false;


$ws_worker->onMessage      = 'onMessage';
$ws_worker->onWorkerStart  = 'onWorkerStart';
$ws_worker->onWorkerReload = 'onWorkerReload';
$ws_worker->onConnect      = 'onConnect';
$ws_worker->onClose        = 'onClose';
/*
 *  @@ onMessage 接收到消息时
 *  @param $connection
 *  @param $data 客户端信息
 * */
function onMessage ($connection, $data) {
    $connection->send('Hello ' . $data);
}
/*
 *   @@ Worker start
 *   @param workerid
 * */
function onWorkerStart ($worker) {
    // in Worker Start Time: printf current process.
    echo "Start Worker : worker Process id = {$worker->id}\n";
    // interval 10ms send messge to client.
    Timer::add(10, function () use ($worker) {
        foreach ($worker->connections as $connection) {
            $connection->send(time());
        }
    });
}
/*
 *  @@ Worker reload
 *  @param $worker
 * */
function onWorkerReload ($worker) {
    foreach ($worker->connections as $connection) {
        $connection->send('Worker reload...');
    }
}
/*
 *  @@ Connection
 *  @param $connection
 * */
function onConnect ($connection) {
    // 在新用户连接时获取客户端IP
    echo "new connection from ip: " . $connection->getRemoteIp() . "\n";
}
/*
 *  @@ onclose
 *  @param $connection
 * */
function onClose ($connection) {
    echo "Ip: " . $connection->getRemoteIp() . "connection close ~~";
}
Worker::runAll();