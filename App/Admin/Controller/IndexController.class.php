<?php

namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

class IndexController extends Controller {
    /*
    *  @@ hospitals select
    *  @param null
    * */
    public function index () {
        $hospitals = M('hospital')->field(array('hospital', 'tableName'))->select();
        $this->assign('hospitals', $hospitals);
        $this->display();
    }
    public function overView () {
        $tableName = $_COOKIE['tableName'];
        if ($tableName == '') return false;
        $isTable = M()->query("show tables like '{$tableName}'");
        if (! $isTable) {
            // create table, return true of false
            if (! $this->createTable($tableName)) return false;
        }
        $situation = $this->custservice(); // 返回一个二维数组
        $arrivalTotal = array_sum(array_column($situation, 'arrivalTotal')); // 求已到总数的和
        $arrival = array_sum(array_column($situation, 'arrival'));
        $arrivalOut = array_sum(array_column($situation, 'arrivalOut'));
        $yesterTotal = array_sum(array_column($situation, 'yestserTotal'));
        $yesterArrival = array_sum(array_column($situation, 'yesterArrival'));
        $yesterArrivalOut = array_sum(array_column($situation, 'yesterArrivalOut'));
        $thisTotal = array_sum(array_column($situation, 'thisTotal'));
        $thisArrival = array_sum(array_column($situation, 'thisArrival'));
        $thisArrivalOut = array_sum(array_column($situation, 'thisArrivalOut'));
        $lastTotal = array_sum(array_column($situation, 'lastTotal'));
        $lastArrival = array_sum(array_column($situation, 'lastArrival'));
        $lastArrivalOut = array_sum(array_column($situation, 'lastArrivalOut'));
        $this->assign('arrivalTotal', $arrivalTotal);
        $this->assign('arrival', $arrival);
        $this->assign('arrivalOut', $arrivalOut);
        $this->assign('yesterTotal', $yesterTotal);
        $this->assign('yesterArrival', $yesterArrival);
        $this->assign('yesterArrivalOut', $yesterArrivalOut);
        $this->assign('thisTotal', $thisTotal);
        $this->assign('thisArrival', $thisArrival);
        $this->assign('thisArrivalOut', $thisArrivalOut);
        $this->assign('lastTotal', $lastTotal);
        $this->assign('lastArrival', $lastArrival);
        $this->assign('lastArrivalOut', $lastArrivalOut);
        $this->assign('appointment', $this->appointment()); // return array.
        $this->assign('thisArrivalSort', $this->thisArrivalList()[0]); // $arrival.
        $this->assign('thisAppointmentSort', $this->thisArrivalList()[1]); // $appointment
        $this->assign('lastArrivalSort', $this->lastArrivalList()[0]);
        $this->assign('lastAppointmentSort', $this->lastArrivalList()[1]);
        $this->display();
    }
    /*
     *  @@ select to make an thisArrival
     *  @param null
     *  @return array() Type: array
     * */
    private function thisArrivalList () {
        $customer = M('custservice')->field('custservice')->select();
        foreach ($customer  as $k => $v) {
            foreach ($v as $c => $d) {
                $customers[] = $d;
            }
        }
        $instance = M($_COOKIE['tableName']);
        for ($i = 0; $i < count($customers); $i ++) {
            $arrival[$customers[$i]] = $instance->where("custService = '{$customers[$i]}' AND status = '已到' AND DATE_FORMAT(currentTime, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $appointment[$customers[$i]] = $instance->where("custService = '{$customers[$i]}' AND status = '预约未定' AND DATE_FORMAT(currentTime, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
        }
        arsort($arrival, SORT_NUMERIC);
        arsort($appointment, SORT_NUMERIC);
        array_splice($arrival, 4);
        array_splice($appointment, 4);
        return array($arrival, $appointment);
    }
    /*
     *  @@ select to make an lastArrival
     *  @param null
     *  @return array() Type: array
     * */
    private function lastArrivalList () {
        $customer = M('custservice')->field('custservice')->select();
        foreach ($customer as $k => $v) {
            foreach ($v as $c => $d) {
                $customers[] = $d;
            }
        }
        $instance = M($_COOKIE['tableName']);
        for ($i = 0; $i < count($customers); $i ++) {
            $arrival[$customers[$i]] = $instance->where("custService = '{$customers[$i]}' AND status = '已到' AND PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'), DATE_FORMAT(oldDate,'%Y%m')) = 1")->count();
            $appointment[$customers[$i]] = $instance->where("custService = '{$customers[$i]}' AND status = '预约未定' AND PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'), DATE_FORMAT(oldDate,'%Y%m')) = 1")->count();
        }
        arsort($arrival, SORT_NUMERIC);
        arsort($appointment, SORT_NUMERIC);
        array_splice($arrival, 4);
        array_splice($appointment, 4);
        return array($arrival, $appointment);
    }
    /*
     *  @@ select To make an appointment in
     *  @param null
     *  @return $appointment Type: array
     * */
    private function appointment () {
        $tableName = $_COOKIE['tableName'];
        $redis = $this->setCache(); // Connect Redis
        if ($redis->exists($tableName . '_appointment')) {
            return json_decode($redis->get($tableName . '_appointment'), true);
        } else {
            $appointment = $this->appointmentSql(); // select to make an appointment in.
            $redis->set($tableName . '_appointment', json_encode($appointment));
            $redis->expire($tableName . '_appointment', 1200);
            return $appointment;
        }
    }
    /*
     *  @@ select to make an appointment in sql
     *  @param null
     *  @return $appointmentData Type: array
     * */
    private function appointmentSql () {
        $instance = M($_COOKIE['tableName']);
        $appointmentData = array();
        $appointmentData['todayTotal'] = $instance->where("TO_DAYS(oldDate) = TO_DAYS(NOW()) AND status = '预约未定'")->count();
        $appointmentData['yesterTotal'] = $instance->where("TO_DAYS(NOW()) - TO_DAYS(oldDate) = 1 AND status = '预约未定'")->count();
        $appointmentData['thisTotal'] = $instance->where("DATE_FORMAT(oldDate, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m') AND status = '预约未定'")->count();
        $appointmentData['lastTotal'] = $instance->where("PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'), DATE_FORMAT(oldDate,'%Y%m')) = 1 AND status = '预约未定'")->count();
        return $appointmentData;
    }
    public function echarts () {
        $this->display();
    }
    public function visit () {
        $arrivalStatus = M('arrivalstatus')->field('arrivalStatus')->select();
        $diseases = M('alldiseases')->where("tableName = '{$_COOKIE['tableName']}'")->field('diseases')->select();
        $custservice = M('custservice')->field('custservice')->select();
        $fromaddress = M('fromaddress')->field('fromaddress')->select();
        $this->assign('arrivalStatus', $arrivalStatus);
        $this->assign('diseases', $diseases);
        $this->assign('custservice', $custservice);
        $this->assign('fromaddress', $fromaddress);
        $this->display();
    }
    /*
     *  @@ visit Data List.
     *  @param null
     *  @return $visitList Type: json.
     * */
    public function visitCheck () {
        $cookietable = $_COOKIE['tableName'];
        $hospital = M($cookietable);
        if ($_GET['search'] == '') {
            $hospitalVisitCount = $hospital->count();
            $hospitalVisit = $hospital->limit(($page = $_GET['page'] - 1) * $_GET['limit'], $_GET['limit'])->order('id desc')->select();
        } else {
            if (is_string($_GET['search'])) {
                $username['name'] = array('like', "%{$_GET['search']}%");
                $hospitalVisitCount = $hospital->where($username)->count();
                $hospitalVisit = $hospital->where($username)->limit(($page = $_GET['page'] - 1) * $_GET['limit'], $_GET['limit'])->order('id desc')->select();
            }
            if (is_numeric($_GET['search'])) {
                $phone['phone'] = array('like', "%{$_GET['search']}%");
                $hospitalVisitCount = $hospital->where($phone)->count();
                $hospitalVisit = $hospital->where($phone)->limit(($page = $_GET['page'] - 1) * $_GET['limit'], $_GET['limit'])->order('id desc')->select();
            }
        }
        $this->arrayRecursive($hospitalVisit, 'urlencode', true);
        $jsonVisit = urldecode(json_encode($hospitalVisit));
        $interval = ceil($hospitalVisitCount / $totalPage);
        $visitList = "{\"code\":0, \"msg\":\"\", \"count\": $hospitalVisitCount, \"data\": $jsonVisit}";
        $this->ajaxReturn($visitList, 'eval');
    }
    /*
     *  @@ visit data delte
     *  @param null
     *  @return boolean Type: eval
     * */
    public function visitDel () {
        if (! is_numeric($_GET['id'])) return false;
        $cookietable = $_COOKIE['tableName'];
        $resovle = M($cookietable)->where("id = {$_GET['id']}")->delete();
        if ($resovle) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *  @@ visit data add
     *  @param null
     *  @return boolean. Type: eval
     * */
    public function addData () {
        $visitData = json_decode($_GET['data'], true);
        $tableName = $_COOKIE['tableName'];
        $resolve = M($tableName)->add($visitData);
        if ($resolve) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *  @@vist data edit
     *  @param null
     *  @return boolean. Type: eval
     * */
    public function editData () {
        $visitData = json_decode($_GET['data'], true);
        $tableName = $_COOKIE['tableName'];
        $resolve = M($tableName)->where("id = {$_GET['id']}")->save($visitData);
        if ($resolve) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *  @hospitals Data List
     *  @param null
     *  @display page.
     * */
    public function hospitalsList () {
        $this->display();
    }
    /*
     *  @hospitals Data Check
     *  @param null
     *  @return $hospitals Type: json.
     * */
    public function hospitalsCheck () {
        $hospitals = M('hospital')->select();
        if ($hospitals) {
            $this->arrayRecursive($hospitals, 'urldecode', true);
        } else {
            $this->ajaxrReturn(false, 'eval');
        }
        $hospitals = urldecode(json_encode($hospitals));
        $hospitalsList = "{\"code\":0, \"msg\":\"\", \"count\": 0, \"data\": $hospitals}";
        $this->ajaxReturn($hospitalsList, 'eval');
    }
    /*
     *  @@ hospitals Data add
     *  @param null
     *  @return boolean
     * */
    public function hospitalsAdd () {
        $hospitalsData = json_decode($_GET['data'], true);
        $resolve = M('hospital')->add($hospitalsData);
        if ($resolve) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *  @@ hospitals Data del
     *  @param null
     *  @return boolean
     * */
    public function hospitalsDel () {
        if (! is_numeric($_GET['id'])) $this->ajaxReturn(false, 'eval');
        $resolve = M('hospital')->where("id = {$_GET['id']}")->delete();
        if ($resolve > 0) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *  @@ disease data
     *  @param null
     *  @display disease
     * */
    public function disease () {
        $this->display();
    }
    /*
     *  @@ disease data check
     *  @param null
     *  @return $diseasesList Type: json
     * */
    public function diseaseCheck () {
        $tableName = $_COOKIE['tableName'];
        $diseases = M('alldiseases')->where("tableName = '{$tableName}'")->field(array('id', 'diseases', 'addtime'))->select();
        if ($diseases) {
            $this->arrayRecursive($diseases, 'urldecode', true);
        } else {
            $this->ajaxReturn(false, 'eval');
        }
        $diseases = urldecode(json_encode($diseases));
        $diseasesList = "{\"code\":0, \"msg\":\"\", \"count\": 0, \"data\": $diseases}";
        $this->ajaxReturn($diseasesList, 'eval');
    }
    /*
     *  @@ diseases data add
     *  @param null
     *  @return boolean
     * */
    public function diseaseAdd () {
        $diseasesData = json_decode($_GET['data'], true);
        $diseasesData['tableName'] = $_COOKIE['tableName'];
        $resolve = M('alldiseases')->add($diseasesData);
        if ($resolve) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *  @@ disease data delete
     *  @param null
     *  @return boolean
     * */
    public function diseaseDel () {
        if (! is_numeric($_GET['id'])) $this->ajaxReturn(false, 'eval');
        $resolve = M('alldiseases')->where("id = {$_GET['id']}")->delete();
        if ($resolve > 0) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *  @@ visitTypesof data list
     *  @param null
     * */
    public function typesof () {
        $this->display();
    }
    /*
     *  @@ typesofCheck data
     *  @@param null
     *  @return boolean
     * */
    public function typesofCheck () {
        $typesof = M('fromaddress')->field(array('id', 'fromaddress', 'addtime'))->select();
        if ($typesof) {
            $this->arrayRecursive($typesof, 'urldecode', true);
        } else {
            $this->ajaxReturn(false, 'eval');
        }
        $typesof = urldecode(json_encode($typesof));
        $typesofList = "{\"code\":0, \"msg\":\"\", \"count\": 0, \"data\": $typesof}";
        $this->ajaxReturn($typesofList, 'eval');
    }
    /*
     *  @@ visitTypesof data delete
     *  @param null
     *  @return boolean
     * */
    public function typesofDel () {
        if (! is_numeric($_GET['id'])) $this->ajaxReturn(false, 'eval');
        $resolve = M('fromaddress')->where("id = {$_GET['id']}")->delete();
        if ($resolve > 0) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *  @@ typesof data add
     *  @param null
     *  @return boolean
     * */
    public function typesofAdd () {
        $typesofData = json_decode($_GET['data'], true);
        $resolve = M('fromaddress')->add($typesofData);
        if ($resolve) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     * @@ doctor data page
     * @param null
     * */
    public function doctor () {
        $this->display();
    }
    /*
     *  @@doctor data list
     *  @param null
     *  @return $custserviceList Type: json
     * */
    public function doctorCheck () {
        $custservice = M('custservice')->field(array('id', 'custservice', 'addtime'))->select();
        if ($custservice) {
            $this->arrayRecursive($custservice, 'urldecode', true);
        } else {
            $this->ajaxReturn(false, 'eval');
        }
        $custservice = urldecode(json_encode($custservice));
        $custserviceList = "{\"code\":0, \"msg\":\"\", \"count\": 0, \"data\": $custservice}";
        $this->ajaxReturn($custserviceList, 'eval');
    }
    /*
     *  @@doctor data add
     *  @param null
     *  @return boolean
     * */
    public function doctorAdd () {
        $doctorData = json_decode($_GET['data'], true);
        $resolve = M('custservice')->add($doctorData);
        if ($resolve) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *  @@doctor data del
     *  @param null
     *  @return boolean
     * */
    public function doctorDel () {
        if (! is_numeric($_GET['id'])) $this->ajaxReturn(false, 'eval');
        $resolve = M('custservice')->where("id = {$_GET['id']}")->delete();
        if ($resolve > 0) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *  @@ arrivalStatus page
     *  @param null
     * */
    public function arrivalStatus () {
        $this->display();
    }
    /*
     *  @@ arrivalStatus data list
     *  @param null
     *  @return $arrivalStatusList Type: json
     * */
    public function arrivalStatusCheck () {
        $arrivalStatus = M('arrivalstatus')->field(array('id', 'arrivalStatus', 'addtime'))->select();
        if ($arrivalStatus) {
            $this->arrayRecursive($arrivalStatus, 'urldecode', true);
        } else {
            $this->ajaxReturn(false, 'eval');
        }
        $arrivalStatus = urldecode(json_encode($arrivalStatus));
        $arrivalStatusList = "{\"code\":0, \"msg\":\"\", \"count\": 0, \"data\": $arrivalStatus}";
        $this->ajaxReturn($arrivalStatusList, 'eval');
    }
    /*
     *  @@ arrivalStatus data del
     *  @param null
     *  @return boolean
     * */
    public function arrivalStatusDel () {
        if (! is_numeric($_GET['id'])) $this->ajaxReturn(false, 'eval');
        $resovle = M('arrivalstatus')->where("id = {$_GET['id']}")->delete();
        if ($resovle) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *  @@ arrivalStatus data add
     *  @param null
     *  @return boolean
     * */
    public function arrivalStatusAdd () {
        $arrivalData = json_decode($_GET['data'], true);
        $resolve = M('arrivalstatus')->add($arrivalData);
        if ($resolve) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *  @@ detail Report table
     *  @param null
     * */
    public function detailReport () {
        $redis = $this->setCache();
        $this->assign('ttl', $redis->ttl($_COOKIE['tableName']));
        $this->display();
    }
    /*
     *  @@ detail Report check
     *  @param null
     *  @return $custserviceList Tyep: json
     * */
    public function detailReportCheck () {
        $redis = $this->setCache();
        $tableName = $_COOKIE['tableName'];
        if ($redis->exists($tableName)) { // 如果为空则写入redis集合.否则直接读取
            $custservice = json_decode($redis->get($tableName), true);
        } else {
            $custservice = $this->custservice();
            $redis->set($tableName, json_encode($custservice));
            $redis->expire($tableName, 1200);
        }
        if ($custservice) {
            $this->arrayRecursive($custservice, 'urldecode', true);
        } else {
            $this->ajaxReturn(false, 'eval');
        }
        $custservice = urldecode(json_encode($custservice));
        $custserviceList = "{\"code\":0, \"msg\":\"\", \"count\": 0, \"data\": $custservice}";
        $this->ajaxReturn($custserviceList, 'eval');
    }
    /*
     *  @@ custservice arrival filter
     *  @param null
     *  @return $custservice Type: array
     * */
    private function custservice () {
        $custservice = M('custservice')->field('custservice')->select();
        $custserviceStrlen = count($custservice);
        $tableName = $_COOKIE['tableName'];
        $arrivalTotal = $this->detail(array( // $this->detial 内部方法调用
            "custservice.custservice, {$tableName}.status, {$tableName}.currentTime",
            array(
                "custservice.custservice = {$tableName}.custservice",
                "{$tableName}.status = '已到'",
                "TO_DAYS(oldDate) = TO_DAYS(NOW())"
            )
        ));
        $arrivalOutTotal = $this->detail(array(
            "custservice.custservice, {$tableName}.status, {$tableName}.currentTime",
            array(
                "custservice.custservice = {$tableName}.custservice",
                "{$tableName}.status = '未到'",
                "TO_DAYS(oldDate) = TO_DAYS(NOW())"
            )
        ));
        $yesterday_arrivalTotal = $this->detail(array(
            "custservice.custservice, {$tableName}.status, {$tableName}.currentTime",
            array(
                "custservice.custservice = {$tableName}.custservice",
                "{$tableName}.status = '已到'",
                "TO_DAYS(NOW()) - TO_DAYS(oldDate) = 1"
            )
        ));
        $yesterday_arrivalOutTotal = $this->detail(array(
            "custservice.custservice, {$tableName}.status, {$tableName}.currentTime",
            array(
                "custservice.custservice = {$tableName}.custservice",
                "{$tableName}.status = '未到'",
                "TO_DAYS(NOW()) - TO_DAYS(oldDate) = 1"
            )
        ));
        $thisMonth_arrivalTotal = $this->detail(array(
            "custservice.custservice, {$tableName}.status, {$tableName}.currentTime",
            array(
                "custservice.custservice = {$tableName}.custservice",
                "{$tableName}.status = '已到'",
                "DATE_FORMAT(oldDate, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m')"
            )
        ));
        $thisMonth_arrivalOutTotal = $this->detail(array(
            "custservice.custservice, {$tableName}.status, {$tableName}.currentTime",
            array(
                "custservice.custservice = {$tableName}.custservice",
                "{$tableName}.status = '未到'",
                "DATE_FORMAT(oldDate, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m')"
            )
        ));
        $lastMonth_arrivalTotal = $this->detail(array(
            "custservice.custservice, {$tableName}.status, {$tableName}.currentTime",
            array(
                "custservice.custservice = {$tableName}.custservice",
                "{$tableName}.status = '已到'",
                "PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'), DATE_FORMAT(oldDate,'%Y%m')) = 1"
            )
        ));
        $lastMonth_arrivalOutTotal = $this->detail(array(
            "custservice.custservice, {$tableName}.status, {$tableName}.currentTime",
            array(
                "custservice.custservice = {$tableName}.custservice",
                "{$tableName}.status = '未到'",
                "PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'), DATE_FORMAT(oldDate,'%Y%m')) = 1"
            )
        ));
        for ($index = 0; $index < $custserviceStrlen; $index ++) {
            $custservice[$index]['arrival'] = array_count_values(array_column($arrivalTotal, 'custservice'))[$custservice[$index]['custservice']];
            $custservice[$index]['arrivalOut'] = array_count_values(array_column($arrivalOutTotal, 'custservice'))[$custservice[$index]['custservice']];
            $custservice[$index]['yesterArrival'] = array_count_values(array_column($yesterday_arrivalTotal, 'custservice'))[$custservice[$index]['custservice']];
            $custservice[$index]['yesterArrivalOut'] = array_count_values(array_column($yesterday_arrivalOutTotal, 'custservice'))[$custservice[$index]['custservice']];
            $custservice[$index]['thisArrival'] = array_count_values(array_column($thisMonth_arrivalTotal, 'custservice'))[$custservice[$index]['custservice']];
            $custservice[$index]['thisArrivalOut'] = array_count_values(array_column($thisMonth_arrivalOutTotal, 'custservice'))[$custservice[$index]['custservice']];
            $custservice[$index]['lastArrival'] = array_count_values(array_column($lastMonth_arrivalTotal, 'custservice'))[$custservice[$index]['custservice']];
            $custservice[$index]['lastArrivalOut'] = array_count_values(array_column($lastMonth_arrivalOutTotal, 'custservice'))[$custservice[$index]['custservice']];
            $custservice[$index]['arrivalTotal'] = $custservice[$index]['arrival'] + $custservice[$index]['arrivalOut'];
            $custservice[$index]['yestserTotal'] = $custservice[$index]['yesterArrival'] + $custservice[$index]['yesterArrivalOut'];
            $custservice[$index]['thisTotal'] = $custservice[$index]['thisArrival'] + $custservice[$index]['thisArrivalOut'];
            $custservice[$index]['lastTotal'] = $custservice[$index]['lastArrival'] + $custservice[$index]['lastArrivalOut'];
        }
        return $custservice;
    }
    /*
     *  @@monthdata
     *  @param null
     *  return   Type: json string
     * */
    public function monthdata () {
        $instance = M($_COOKIE['tableName']);
        $redis = $this->setCache();
        if ($redis->exists('arrival')) {
            $arrival = json_decode($redis->get('arrival'), true);
        } else {
            $arrival = array();
            $arrival['reser'] = $instance->where("status = '预约未定' AND DATE_FORMAT(currentTime, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $arrival['advan'] = $instance->where("status = '等待' AND DATE_FORMAT(currentTime, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $arrival['arrival'] = $instance->where("status = '已到' AND DATE_FORMAT(currentTime, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $arrival['arrivalOut'] = $instance->where("status = '未到' AND DATE_FORMAT(currentTime, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $arrival['halfTotal'] = $instance->where("status = '全流失' AND DATE_FORMAT(currentTime, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $arrival['half'] = $instance->where("status = '半流失' AND DATE_FORMAT(currentTime, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $arrival['treat'] = $instance->where("status = '已诊治' AND DATE_FORMAT(currentTime, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $redis->set('arrival', json_encode($arrival));
            $redis->expire('arrival', 1200);
        }
        $this->assign('arrival', $arrival);
        $this->display();
    }
    /*
     *  @@ detail function
     *  @param $array Type: array[0] condition, array[1] field.
     *  @return $allStatus Type: array
     * */
    private function detail ($array) {
        $tableName = $_COOKIE['tableName'];
        $allStatus = M('custservice')->join($tableName)->field($array[0])->where($array[1])->select();
        return $allStatus;
    }
    /*
     *  @@ JsonString handle
     *  @param $array Type: array
     *  @param $function Type: string
     *  @param $apply_to_keys_also Type: boolean
     *  @return jsonString
     * */
    private function arrayRecursive(&$array, $function, $apply_to_keys_also = false) {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }
            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        $recursive_counter--;
    }
    /*
     *  @@user access page
     *  @param null
     * */
    public function access () {
        $this->display();
    }
    /*
     *  @@user access check
     *  @param null
     *  @return $userList Type: josn
     * */
    public function accessCheck () {
        $user = M('user')->select();
        if ($user) {
            $this->arrayRecursive($user, 'urldecode', true);
        } else {
            $this->ajaxrReturn(false, 'eval');
        }
        $user = urldecode(json_encode($user));
        $userList = "{\"code\":0, \"msg\":\"\", \"count\": 0, \"data\": $user}";
        $this->ajaxReturn($userList, 'eval');
    }
    /*
     *  @@userDel
     *  @param null
     *  @return boolean Type: eval
     * */
    public function userDel () {
        if (! is_numeric($_GET['id'])) $this->ajaxReturn(false, 'eval');
        $resovle = M('user')->where("id = {$_GET['id']}")->delete();
        if ($resovle) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *  @@expansion connect redis.
     *  @param null.
     *  @return $redis. Type: instance
     * */
    private function setCache () {
        try {
            $redis = new \Redis();
            $redis->connect('211.149.x.x', 6379);
            $redis->auth('xxxxxx');
            $redis->select(1);
        } catch (Exception $e) {
            die ("Connect Redis Fail: " . $e->getMessage());
        }
        return $redis;
    }
    /*
     *  @@ create new table
     *  @param $tableName cookie
     *  @retur boolean
     * */
    private function createTable ($tableName) {
        $sql = <<<sql
              CREATE TABLE  `$tableName` (
              `id` int NOT NULL AUTO_INCREMENT,
              `name` varchar(15) NOT NULL,
              `old` int NOT NULL,
              `phone` bigint(20) NOT NULL,
              `qq` bigint(20) NOT NULL,
              `diseases` varchar(30) NOT NULL,
              `fromAddress` varchar(15) NOT NULL,
              `switch` varchar(15) NOT NULL DEFAULT '外地',
              `sex` varchar(15) NOT NULL DEFAULT '男',
              `desc1` varchar(300) NOT NULL,
              `expert` varchar(10) NOT NULL,
              `oldDate` date NOT NULL,
              `desc2` varchar(300) NOT NULL,
              `status` varchar(15) NOT NULL,
              `newDate` date NOT NULL,
              `currentTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
              `custService` varchar(30) NOT NULL,
              PRIMARY KEY(`id`),
              KEY `oldDate` (`oldDate`),
              KEY `status` (`status`)
              ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
sql;
        if (M()->query($sql)) return true;
        return false;
    }
}