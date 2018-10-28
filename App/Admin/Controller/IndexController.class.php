<?php

namespace Admin\Controller;
use Think\Controller;
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
        $this->display();
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
        $this->display();
    }
    /*
     *  @@ detail Report check
     *  @param null
     *  @return $custserviceList Tyep: json
     * */
    public function detailReportCheck () {
        $custservice = $this->custservice(); // $this->custservice 内部方法调用
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
            "custservice.custservice, {$tableName}.status",
            array("custservice.custservice = {$tableName}.custservice", "{$tableName}.status = '已到'", "TO_DAYS(oldDate) = TO_DAYS(NOW())")
        ));
        $arrivalOutTotal = $this->detail(array(
            "custservice.custservice, {$tableName}.status",
            array("custservice.custservice = {$tableName}.custservice", "{$tableName}.status = '未到'", "TO_DAYS(oldDate) = TO_DAYS(NOW())")
        ));
        $yesterday_arrivalTotal = $this->detail(array(
            "custservice.custservice, {$tableName}.status",
            array("custservice.custservice = {$tableName}.custservice", "{$tableName}.status = '已到'", "DATE_FORMAT(oldDate, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m')")
        ));
        $yesterday_arrivalOutTotal = $this->detail(array(
            "custservice.custservice, {$tableName}.status",
            array("custservice.custservice = {$tableName}.custservice", "{$tableName}.status = '未到'", "DATE_FORMAT(oldDate, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m')")
        ));
        $thisMonth_arrivalTotal = $this->detail(array(
            "custservice.custservice, {$tableName}.status",
            array("custservice.custservice = {$tableName}.custservice", "{$tableName}.status = '已到'", "DATE_FORMAT(oldDate, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m')")
        ));
        $thisMonth_arrivalOutTotal = $this->detail(array(
            "custservice.custservice, {$tableName}.status",
            array("custservice.custservice = {$tableName}.custservice", "{$tableName}.status = '未到'", "DATE_FORMAT(oldDate, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m')")
        ));
        $lastMonth_arrivalTotal = $this->detail(array(
            "custservice.custservice, {$tableName}.status",
            array("custservice.custservice = {$tableName}.custservice", "{$tableName}.status = '已到'", "PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'), DATE_FORMAT(oldDate,'%Y%m'))")
        ));
        $lastMonth_arrivalOutTotal = $this->detail(array(
            "custservice.custservice, {$tableName}.status",
            array("custservice.custservice = {$tableName}.custservice", "{$tableName}.status = '未到'", "PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'), DATE_FORMAT(oldDate,'%Y%m'))")
        ));
        for ($index = 0; $index < $custserviceStrlen; $index ++) {
            $custservice[$index] = $custservice[$index];
            $custservice[$index]['arrival'] = array_count_values(array_column($arrivalTotal, 'custservice'))[$custservice[$index]['custservice']];
            $custservice[$index]['arrivalOut'] = array_count_values(array_column($arrivalOutTotal, 'custservice'))[$custservice[$index]['custservice']];
            $custservice[$index]['yesterArrival'] = array_count_values(array_column($yesterday_arrivalTotal, 'custservice'))[$custservice[$index]['custservice']];
            $custservice[$index]['yesterArrivalOut'] = array_count_values(array_column($yesterday_arrivalOutTotal, 'custservice'))[$custservice[$index]['custservice']];
            $custservice[$index]['thisArrival'] = array_count_values(array_column($thisMonth_arrivalTotal, 'custservice'))[$custservice[$index]['custservice']];
            $custservice[$index]['thisArrivalOut'] = array_count_values(array_column($thisMonth_arrivalOutTotal, 'custservice'))[$custservice[$index]['custservice']];
            $custservice[$index]['lastArrival'] = array_count_values(array_column($lastMonth_arrivalTotal, 'custservice'))[$custservice[$index]['custservice']];
            $custservice[$index]['lastArrivalOut'] = array_count_values(array_column($lastMonth_arrivalTotal, 'custservice'))[$custservice[$index]['custservice']];
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
     *  @@expansion set Cache.
     *  @param $string Type: String.
     *  @return data. Type: json
     * */
    public function setCache ($string) {
        $redis = new \Redis();
        $redis->connect('x.x.x.x', 6379);
        $redis->auth('xxxx');
        $redis->select(1);
        $redis->set('string', $string);
        if ((boolean) $redis->exists('string')) {
            return true;
        } else {
            return false;
        }
    }
}