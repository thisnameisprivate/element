<?php

namespace Admin\Model;
use Think\Model;

class CollectionModel extends Model {
    private $collectionConf =
        array(  // status
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
    private $conditions =
        array( // MYSQL DATE SELCET
        0 => "TO_DAYS(oldDate) = TO_DAYS(NOW())",
        1 => "TO_DAYS(NOW()) - TO_DAYS(oldDate) = 1",
        2 => "DATE_FORMAT(oldDate, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m')",
        3 => "PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'), DATE_FORMAT(oldDate,'%Y%m')) = 1"
    );
    /*
     * @@ 表单下拉列表框
     * */
    public function selectOption () {
        $selectCollection['arrivalStatus']  = M('arrivalstatus')->field('arrivalStatus')->select();
        $selectCollection['diseases']       = M('alldiseases')->where("tableName = '{$_COOKIE['tableName']}'")->field('diseases')->select();
        $selectCollection['custservice']    = M('custservice')->field('custservice')->select();
        $selectCollection['fromaddress']    = M('fromaddress')->field('fromaddress')->select();
        return $selectCollection;
    }
    /*
     *   @@ 导出数据
     *   @param null
     *   @return array
     * */
    public function derive () {

    }
    /*
     *  @@ 按时间/状态查询
     *  @param $request Type: GET array
     *  @param $status Type: Controller $this->statusSuffixConf();
     *  @return array
     * */
    public function specifiedFunc ($request, $status) {
        $collectionConf = $this->collectionConf;
        $conditions = $this->conditions;
        $iden = $request['iden'];
        $appCom = '预约未定';
        $cookietable = $_COOKIE['tableName'];
        $hospital = M($cookietable);
        if ($iden == $collectionConf[0]) {
            $hospitalVisitCount = $hospital->where(array($conditions[0]))->count();
            $hospitalVisit = $hospital->where(array($conditions[0]))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        } else if ($iden == $collectionConf[1]) {
            $hospitalVisitCount = $hospital->where(array($conditions[0], "status = '{$status['arrival']}'"))->count();
            $hospitalVisit = $hospital->where(array($conditions[0], "status = '{$status['arrival']}'"))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        } else if ($iden == $collectionConf[2]) {
            $hospitalVisitCount = $hospital->where(array($conditions[0], "status != '{$status['arrival']}'"))->count();
            $hospitalVisit = $hospital->where(array($conditions[0], "status != '{$status['arrival']}'"))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        } else if ($iden == $collectionConf[3]) {
            $hospitalVisitCount = $hospital->where(array($conditions[1]))->count();
            $hospitalVisit = $hospital->where(array($conditions[1]))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        } else if ($iden == $collectionConf[4]) {
            $hospitalVisitCount = $hospital->where(array($conditions[1], "status = '{$status['arrival']}'"))->count();
            $hospitalVisit = $hospital->where(array($conditions[1], "status = '{$status['arrival']}'"))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        } else if ($iden == $collectionConf[5]) {
            $hospitalVisitCount = $hospital->where(array($conditions[1], "status != '{$status['arrival']}'"))->count();
            $hospitalVisit = $hospital->where(array($conditions[1], "status != '{$status['arrival']}'"))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        } else if ($iden == $collectionConf[6]) {
            $hospitalVisitCount = $hospital->where(array($conditions[2]))->count();
            $hospitalVisit = $hospital->where(array($conditions[2]))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        } else if ($iden == $collectionConf[7]) {
            $hospitalVisitCount = $hospital->where(array($conditions[2], "status = '{$status['arrival']}'"))->count();
            $hospitalVisit = $hospital->where(array($conditions[2], "status = '{$status['arrival']}'"))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        } else if ($iden == $collectionConf[8]) {
            $hospitalVisitCount = $hospital->where(array($conditions[2], "status != '{$status['arrival']}'"))->count();
            $hospitalVisit = $hospital->where(array($conditions[2], "status != '{$status['arrival']}'"))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        } else if ($iden == $collectionConf[9]) {
            $hospitalVisitCount = $hospital->where(array($conditions[3]))->count();
            $hospitalVisit = $hospital->where(array($conditions[3]))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        } else if ($iden == $collectionConf[10]) {
            $hospitalVisitCount = $hospital->where(array($conditions[3], "status = '{$status['arrival']}'"))->count();
            $hospitalVisit = $hospital->where(array($conditions[3], "status = '{$status['arrival']}'"))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        } else if ($iden == $collectionConf[11]) {
            $hospitalVisitCount = $hospital->where(array($conditions[3], "status != '{$status['arrival']}'"))->count();
            $hospitalVisit = $hospital->where(array($conditions[3], "status != '{$status['arrival']}'"))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        } else if ($iden == $collectionConf[12]) {
            $hospitalVisitCount = $hospital->where(array($conditions[0], "status = '{$appCom}'"))->count();
            $hospitalVisit = $hospital->where(array($conditions[0], "status = '{$appCom}'"))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        } else if ($iden == $collectionConf[13]) {
            $hospitalVisitCount = $hospital->where(array($conditions[1], "status = '{$appCom}'"))->count();
            $hospitalVisit = $hospital->where(array($conditions[1], "status = '{$appCom}'"))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        } else if ($iden == $collectionConf[14]) {
            $hospitalVisitCount = $hospital->where(array($conditions[2], "status = '{$appCom}'"))->count();
            $hospitalVisit = $hospital->where(array($conditions[2], "status = '{$appCom}'"))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        } else if ($iden == $collectionConf[15]) {
            $hospitalVisitCount = $hospital->where(array($conditions[3], "status = '{$appCom}'"))->count();
            $hospitalVisit = $hospital->where(array($conditions[3], "status = '{$appCom}'"))->limit(($page = $request['page'] - 1) * $request['limit'], $request['limit'])->order('id desc')->select();
        }
        return array($hospitalVisit, $hospitalVisitCount);
    }
}