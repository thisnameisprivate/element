<?php

namespace Admin\Model;
use Think\Model;

class CollectionModel extends Model {
    /**
     * @@ Status Config
     *
     */
    private $collectionConf =
        array(
        0  => 'arrivalTotal',
        1  => 'arrival',
        2  => 'arrivalOut',
        3  => 'yesterTotal',
        4  => 'yesterArrival',
        5  => 'yesterArrivalOut',
        6  => 'thisTotal',
        7  => 'thisArrival',
        8  => 'thisArrivalOut',
        9  => 'lastTotal',
        10 => 'lastArrival',
        11 => 'lastArrivalOut',
        12 => 'appTodayTotal',
        13 => 'appYesterTotal',
        14 => 'appThisTotal',
        15 => 'appLastTotal'
    );
    /*
     * @@ SQL Syntax
     * */
    private $conditions =
        array(
        0 => "TO_DAYS(oldDate) = TO_DAYS(NOW())",
        1 => "TO_DAYS(NOW()) - TO_DAYS(oldDate) = 1",
        2 => "DATE_FORMAT(oldDate, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m')",
        3 => "PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'), DATE_FORMAT(oldDate,'%Y%m')) = 1"
    );
    /*
     * @@ 表单下拉列表框
     * @return $selectCollection Type: array
     * */
    public function selectOption () {
        $selectCollection['arrivalStatus']  = M('arrivalstatus')->field('arrivalStatus')->select();
        $selectCollection['diseases']       = M('alldiseases')->where("tableName = '{$_COOKIE['tableName']}'")->field('diseases')->select();
        $selectCollection['custservice']    = M('custservice')->field('custservice')->select();
        $selectCollection['fromaddress']    = M('fromaddress')->field('fromaddress')->select();
        return $selectCollection;
    }
    /*
     * @@数据导出 按时间范围/状态查询
     * @return array Type: 二维数组
     * */
    public function resources ($request) {
        $tableName = $_COOKIE['tableName'];
        if (empty($request)) {
            $hospitalVisitCount = M($tableName)->where(array("oldDate > '{$_GET['date_min']}'", "oldDate < '{$_GET['date_max']}'"))->count();
            $hospitalVisit = M($tableName)->where(array("oldDate > '{$_GET['date_min']}'", "oldDate < '{$_GET['date_max']}'"))->select();
        } else {
            $resolve = $request['status'] == '已到' ? "status = '{$request['status']}'" : "status != '已到'";
            $hospitalVisitCount = M($tableName)->where(array("oldDate > '{$_GET['date_min']}'", "oldDate < '{$_GET['date_max']}'", $resolve))->count();
            $hospitalVisit = M($tableName)->where(array("oldDate > '{$_GET['date_min']}'", "oldDate < '{$_GET['date_max']}'", $resolve))->select();
        }
        return array($hospitalVisit, $hospitalVisitCount);
    }
    /**
     * @@根据电话查看数据是否存在
     * @param $request string
     * @return array
     */
    public function addDataSelect ($request) {
        $tableName = $_COOKIE['tableName'];
        $resolve = M($tableName)->where("phone = '{$request}'")->select();
        if ($resolve) return $resolve;
        return;
    }
    /*
     *  @@首页点击 按时间/状态查询
     *  @param $request Type: GET array
     *  @param $status Type: Controller $this->statusSuffixConf();
     *  @return array
     * */
    public function specifiedFunc ($request, $status) {
        $collectionConf = $this->collectionConf;
        $conditions = $this->conditions;
        $cookietable = $_COOKIE['tableName'];
        $hospital = M($cookietable);
        switch ($request['iden']) {
            case $collectionConf[0]:
                $hospitalVisitCount  = $hospital->where(array($conditions[0]))->count();
                $hospitalVisit       = $hospital->where(array($conditions[0]))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order("id desc")->select();
                break;
            case $collectionConf[1]:
                $hospitalVisitCount  = $hospital->where(array($conditions[0], "status = '{$status['arrival']}'"))->count();
                $hospitalVisit       = $hospital->where(array($conditions[0], "status = '{$status['arrival']}'"))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
                break;
            case $collectionConf[2]:
                $hospitalVisitCount  = $hospital->where(array($conditions[0], "status != '{$status['arrival']}'"))->count();
                $hospitalVisit       = $hospital->where(array($conditions[0], "status != '{$status['arrival']}'"))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
                break;
            case $collectionConf[3]:
                $hospitalVisitCount  = $hospital->where(array($conditions[1]))->count();
                $hospitalVisit       = $hospital->where(array($conditions[1]))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order("id desc")->select();
                break;
            case $collectionConf[4]:
                $hospitalVisitCount  = $hospital->where(array($conditions[1], "status = '{$status['arrival']}'"))->count();
                $hospitalVisit       = $hospital->where(array($conditions[1], "status = '{$status['arrival']}'"))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
                break;
            case $collectionConf[5]:
                $hospitalVisitCount  = $hospital->where(array($conditions[1], "status != '{$status['arrival']}'"))->count();
                $hospitalVisit       = $hospital->where(array($conditions[1], "status != '{$status['arrival']}'"))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
                break;
            case $collectionConf[6]:
                $hospitalVisitCount  = $hospital->where(array($conditions[2]))->count();
                $hospitalVisit       = $hospital->where(array($conditions[2]))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order("id desc")->select();
                break;
            case $collectionConf[7]:
                $hospitalVisitCount  = $hospital->where(array($conditions[2], "status = '{$status['arrival']}'"))->count();
                $hospitalVisit       = $hospital->where(array($conditions[2], "status = '{$status['arrival']}'"))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
                break;
            case $collectionConf[8]:
                $hospitalVisitCount  = $hospital->where(array($conditions[2], "status != '{$status['arrival']}'"))->count();
                $hospitalVisit       = $hospital->where(array($conditions[2], "status != '{$status['arrival']}'"))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
                break;
            case $collectionConf[9]:
                $hospitalVisitCount  = $hospital->where(array($conditions[3]))->count();
                $hospitalVisit       = $hospital->where(array($conditions[3]))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order("id desc")->select();
                break;
            case $collectionConf[10]:
                $hospitalVisitCount  = $hospital->where(array($conditions[3], "status = '{$status['arrival']}'"))->count();
                $hospitalVisit       = $hospital->where(array($conditions[3], "status = '{$status['arrival']}'"))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
                break;
            case $collectionConf[11]:
                $hospitalVisitCount  = $hospital->where(array($conditions[3], "status != '{$status['arrival']}'"))->count();
                $hospitalVisit       = $hospital->where(array($conditions[3], "status != '{$status['arrival']}'"))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
                break;
            case $collectionConf[12]:
                $hospitalVisitCount  = $hospital->where(array($condition[0], "status = '预约未定'"))->count();
                $hospitalVisit       = $hospital->where(array($conditions[0], "status = '预约未定'"))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
                break;
            case $collectionConf[13]:
                $hospitalVisitCount  = $hospital->where(array($condition[1], "status = '预约未定'"))->count();
                $hospitalVisit       = $hospital->where(array($conditions[1], "status = '预约未定'"))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
                break;
            case $collectionConf[14]:
                $hospitalVisitCount  = $hospital->where(array($condition[2], "status = '预约未定'"))->count();
                $hospitalVisit       = $hospital->where(array($conditions[2], "status = '预约未定'"))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
                break;
            case $collectionConf[15]:
                $hospitalVisitCount  = $hospital->where(array($condition[3], "status = '预约未定'"))->count();
                $hospitalVisit       = $hospital->where(array($conditions[3], "status = '预约未定'"))->limit(($request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
                break;
        }
        return array($hospitalVisit, $hospitalVisitCount);
    }
}














































