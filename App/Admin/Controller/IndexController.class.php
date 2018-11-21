<?php
/* ****************************************************************************************************************
 *                                                                                                               **
 *  This system is used to monitor the status of hospital return visit information registration.                 **
 *  real-time communication Echarts graph trend/distribution, and to monitor the situation of diagnosis.         **
 *  The architecture USES Redis, MySQL, PHP, Swolle [MeepoPS ThinkPHP framework].                                **
 *  Developer: KeXin; Creation time: 2018/10/27.                                                                 **
 *  Acknowledgement: KeXin; Development cycle: February - no maintenance stop.                                   **
 *  Maintenance staff: kexin                                                                                     **
 *                                                                                                               **
 * ****************************************************************************************************************
 * ****************************************************************************************************************
 * */
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;
class IndexController extends Controller {
    /*
    *  @@ hospitals select
    *  @param null
    * */
    public function index () {
        $userCookie = $_COOKIE['username'];
        if (is_null($userCookie)) {
            $this->error("please login", U("Home/Index/index"));
        }
        $userAcc = $this->userManagement($userCookie);
        if ($userAcc) $this->assign('userAcc', json_encode($userAcc)); // 权限验证 传值到前端用于储存localStorage.
        $hospitals = M('hospital')->field(array('hospital', 'tableName'))->select();
        $this->assign('hospitals', $hospitals);
        $this->display();
    }
    /*
     *  @@ userManagement select
     *  @@param null
     *  @return $userAcc Type: array
     *
     * */
    private function userManagement ($userCookie) {
        $userAcc = M('management')->where("pid = '{$userCookie}'")->select();
        if ($userAcc)  {
            foreach ($userAcc as $k => $v) return $v;
        } else {
            return false;
        }
    }
    /*
     *  @@ overView Home page.
     * */
    public function overView () {
        $tableName = $_COOKIE['tableName'];
        if ($tableName == '') return false;
        /* ******************************************************************************
         * ******************************************************************************
         *                                                                             **
         *  Changes made for PHP compatibility with version 5.3.                       **
         *  Variable declaration php_5.3num1 or num2                                   **
         *  Author: kexin                                                              **
         *  Date: 2018-11-8.                                                           **
         *                                                                             **
         * ******************************************************************************
         * */
        $isTable = M()->query("show tables like '{$tableName}'");
        if (! $isTable) {if (! $this->createTable($tableName)) return false; }
        $redis = $this->setCache();
        if ($redis->exists($tableName . '_arrivalTotal')) {
            $keyNames = $redis->keys($tableName . "*"); // get all key.
            $statusSuffixConf = $this->statusSuffixConf(); // get cache time 300s.
            for ($i = 0; $i < count($keyNames); $i ++) {
                $str = $redis->get($keyNames[$i]);
                if (! substr($str, 0, 1) == '{') {
                    $strIden = explode('_', $keyNames[$i]);
                    $this->assign($strIden[1], $str);
                } else {
                    $strIden = explode('_', $keyNames[$i]);
                    $this->assign($strIden[1], json_decode($str, true));
                }
                $redis->expire($keyNames[$i], $statusSuffixConf['endTime']);
            }
        } else {
            $collection = $this->custservice(); // 返回一个二维数组
            $this->assign('arrivalTotal', $collection['arrivalTotal']);
            $this->assign('arrival', $collection['arrival']);
            $this->assign('arrivalOut', $collection['arrivalOut']);
            $this->assign('yesterTotal', $collection['yesterTotal']);
            $this->assign('yesterArrival', $collection['yesterArrival']);
            $this->assign('yesterArrivalOut', $collection['yesterArrivalOut']);
            $this->assign('thisTotal', $collection['thisTotal']);
            $this->assign('thisArrival', $collection['thisArrival']);
            $this->assign('thisArrivalOut', $collection['thisArrivalOut']);
            $this->assign('lastTotal', $collection['lastTotal']);
            $this->assign('lastArrival', $collection['lastArrival']);
            $this->assign('lastArrivalOut', $collection['lastArrivalOut']);
            while (list ($k, $v) = each ($collection)) $this->arrivalSetRedis($tableName . "_" . $k, $v);
        }
        /* ******************************************************************************
         * ******************************************************************************
         *                                                                             **
         *  The data will be written to Redis middleware later                         **
         *  But I don't have much time to code at the moment.                          **
         *  Author: kexin                                                              **
         *  Date: 2018-11-3.                                                           **
         *                                                                             **
         * ******************************************************************************
         * */
        $thisArrivalList = $this->thisArrivalList();
        $lastArrivalList = $this->lastArrivalList();
        $this->assign('appointment', $this->appointment()); // return sort array.
        $this->assign('thisArrivalSort', $thisArrivalList[0]);
        $this->assign('thisAppointmentSort', $thisArrivalList[1]);
        $this->assign('lastArrivalSort', $lastArrivalList[0]);
        $this->assign('lastAppointmentSort', $lastArrivalList[1]);
        $this->display();
    }
    /*
     * @@specifed search home
     *
     * */
    public function specified () {
        $this->assign('iden', $_GET['iden']);
        // select option value.
        $selectOption = D('Collection')->selectOption();
        $this->assign('arrivalStatus', $selectOption['arrivalStatus']);
        $this->assign('diseases', $selectOption['diseases']);
        $this->assign('custservice', $selectOption['custservice']);
        $this->assign('fromaddress', $selectOption['fromaddress']);
        $this->display();
    }
    /*
     *  @@specified search
     *  @@param iden Type: String
     *
     * */
    public function specifiedCheck () {
        $hospitalVisit = D('Collection')->specifiedFunc($_GET, $this->statusSuffixConf());
        $hospitalVisitCount = $hospitalVisit[1];
        // trim array \t
        $hospitalVisit = $this->arraySplice($hospitalVisit[0]); // array splice get.
        $this->arrayRecursive($hospitalVisit, 'urlencode', true);
        $jsonVisit = urldecode(json_encode($hospitalVisit));
        $visitList = "{\"code\":0, \"msg\":\"\", \"count\": $hospitalVisitCount, \"data\": $jsonVisit}";
        /* ******************************************************************************
         * ******************************************************************************
         *                                                                             **
         *  This replacep param must is '\n'.                                          **
         *  Time wasted here: 5 hours                                                  **
         *  Author: kexin                                                              **
         *  Date: 2018-11-5.                                                           **
         *                                                                             **
         * ******************************************************************************
         * */
        $this->ajaxReturn(str_replace(array("\n", "\r"), '\n', $visitList), 'eval');
    }
    /*
     *   @@arrival Set Redis
     *   @param key Type: string
     *   @param value Type: variable int.
     *   @return Boolean Type: Boolean.
     * */
    private function arrivalSetRedis ($key, $value) {
        $redis = $this->setCache();
        $redis->set($key, $value);
        $statusSuffixConf = $this->statusSuffixConf();
        $redis->expire($key, $statusSuffixConf['endTime']);
    }
    /*
     *  @@ select to make an thisArrival
     *  @param null
     *  @return array() Type: array
     * */
    private function thisArrivalList () {
        $customer = M('custservice')->field('custservice')->select();
        foreach ($customer  as $k => $v) foreach ($v as $c => $d) $customers[] = $d;
        $instance = M($_COOKIE['tableName']);
        for ($i = 0; $i < count($customers); $i ++) {
            $arrival[$customers[$i]] = $instance->where("custService = '{$customers[$i]}' AND status = '已到' AND DATE_FORMAT(oldDate, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $appointment[$customers[$i]] = $instance->where("custService = '{$customers[$i]}' AND status = '预约未定' AND DATE_FORMAT(oldDate, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
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
        foreach ($customer as $k => $v) foreach ($v as $c => $d) $customers[] = $d;
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
        $appointmentData['todayTotal']   = $instance->where("TO_DAYS(oldDate) = TO_DAYS(NOW()) AND status = '预约未定'")->count();
        $appointmentData['yesterTotal']  = $instance->where("TO_DAYS(NOW()) - TO_DAYS(oldDate) = 1 AND status = '预约未定'")->count();
        $appointmentData['thisTotal']    = $instance->where("DATE_FORMAT(oldDate, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m') AND status = '预约未定'")->count();
        $appointmentData['lastTotal']    = $instance->where("PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'), DATE_FORMAT(oldDate,'%Y%m')) = 1 AND status = '预约未定'")->count();
        return $appointmentData;
    }
    public function echarts () {
        $this->display();
    }
    public function visit () {
        $selectOption = D('Collection')->selectOption();
        $this->assign('arrivalStatus', $selectOption['arrivalStatus']);
        $this->assign('diseases', $selectOption['diseases']);
        $this->assign('custservice', $selectOption['custservice']);
        $this->assign('fromaddress', $selectOption['fromaddress']);
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
        // trim array \t
        $hospitalVisit = $this->arraySplice($hospitalVisit);
        $this->arrayRecursive($hospitalVisit, 'urlencode', true);
        $jsonVisit = urldecode(json_encode($hospitalVisit));
        $interval = ceil($hospitalVisitCount / $totalPage);
        $visitList = "{\"code\":0, \"msg\":\"\", \"count\": $hospitalVisitCount, \"data\": $jsonVisit}";
        /* ******************************************************************************
         * ******************************************************************************
         *                                                                             **
         *  This replacep param must is '\n'.                                          **
         *  Time wasted here: 5 hours                                                  **
         *  Author: kexin                                                              **
         *  Date: 2018-11-5.                                                           **
         *                                                                             **
         * ******************************************************************************
         * */
        $this->ajaxReturn(str_replace(array("\n", "\r"), '\n', $visitList), 'eval');
    }
    /*
     *  @@ visit data delte
     *  @param null
     *  @return boolean Type: eval
     * */
    public function visitDel () {
        $visitData = json_decode($_GET['data'], true);
        $cookietable = $_COOKIE['tableName'];
        $resovle = M($cookietable)->where("id = '{$visitData['id']}'")->delete();
        if ($resovle) {
            $this->writeDataLpushRedis("decr", $visitData);
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
            $this->writeDataLpushRedis("incr", $visitData); // incr redis...set
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *  @@statusSuffixConf
     *  @return $statusSuffix Type: array
     * */
    private function statusSuffixConf () {
        return array(
            'arrival'       => '已到',
            'arrivalOut'    => '未到',
            'arrivalStr'    => $_COOKIE['tableName'] . '_arrival',
            'arrivalOutStr' => $_COOKIE['tableName'] . '_arrivalOut',
            'endTime'       => 300
        );
    }
    /*
     *  @@Redis List.
     *  @@param $data Type: array.
     *  @@Redis lPush new Data
     * */
    private function writeDataLpushRedis ($operation, $data) {
        $tableName = $_COOKIE['tableName'];
        $stateCollegeConf = array(
            0   => $tableName . '_arrivalTotal',
            1   => $tableName . '_arrival',
            2   => $tableName . '_arrivalOut',
            3   => $tableName . '_yesterTotal',
            4   => $tableName . '_yesterArrival',
            5   => $tableName . '_yesterArrivalOut',
            6   => $tableName . '_thisTotal',
            7   => $tableName . '_thisArrival',
            8   => $tableName . '_thisArrivalOut',
            9   => $tableName . '_lastTotal',
            10  => $tableName . '_lastArrival',
            11  => $tableName . '_lastArrivalOut'
        );
        $statusSuffix = $this->statusSuffixConf();
        $redis = $this->setCache();
        if (date('d', time($data['oldDate'])) == (date("d"))) {
            if ($data['status'] == $statusSuffix['arrival']) {
                $redis->$operation($stateCollegeConf[0]);
                $redis->$operation($stateCollegeConf[1]);
            }
            if ($data['status'] == $statusSuffix['arrivalOut']) {
                $redis->$operation($stateCollegeConf[0]);
                $redis->$operation($stateCollegeConf[2]);
            }
        } else if (date('d', time($data['oldDate'])) == date("d", strtotime("-1 day"))) {
            if ($data['status'] == $statusSuffix['arrival']) {
                $redis->$operation($stateCollegeConf[3]);
                $redis->$operation($stateCollegeConf[4]);
            }
            if ($data['status'] == $statusSuffix['arrivalOut']) {
                $redis->$operation($stateCollegeConf[3]);
                $redis->$operation($stateCollegeConf[5]);
            }
        }
        if (date('m', time($data['oldDate'])) == date("m")) {
            if ($data['status'] == $statusSuffix['arrival']) {
                $redis->$operation($stateCollegeConf[6]);
                $redis->$operation($stateCollegeConf[7]);
            }
            if ($data['status'] == $statusSuffix['arrivalOut']) {
                $redis->$operation($stateCollegeConf[6]);
                $redis->$operation($stateCollegeConf[8]);
            }
        } else if (date('m', time($data['oldDate'])) == date("m", strtotime("-1 month"))) {
            if ($data['status'] == $statusSuffix['arrival']) {
                $redis->$operation($stateCollegeConf[9]);
                $redis->$operation($stateCollegeConf[10]);
            }
            if ($data['status'] == $statusSuffix['arrivalOut']) {
                $redis->$operation($stateCollegeConf[9]);
                $redis->$operation($stateCollegeConf[11]);
            }
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
            $pserionCollection = json_decode($redis->get($tableName), true);
        } else {
            $pserionCollection = $this->persionCollection();
            $redis->set($tableName, json_encode($pserionCollection));
            $redis->expire($tableName, 1200);
        }
        if ($pserionCollection) {
            $this->arrayRecursive($pserionCollection, 'urldecode', true);
        } else {
            $this->ajaxReturn(false, 'eval');
        }
        $pserionCollection = urldecode(json_encode($pserionCollection));
        $pserionCollectionList = "{\"code\":0, \"msg\":\"\", \"count\": 0, \"data\": $pserionCollection}";
        $this->ajaxReturn($pserionCollectionList, 'eval');
    }
    /*
     *  @@detail Report check Persion
     *  @@param null
     *  @return $persionCollList persion Collection.
     * */
    private function persionCollection () {
        $persion = M('custservice')->field('custservice')->select();
        foreach ($persion as $k => $v) foreach ($v as $key => $value) $keyNames[$k] = $value;
        $persionCollection = array();
        while (list ($k, $v) = each ($keyNames)) {
            $persionCollection[$v]['arrival']           = $this->detail("custservice = '{$v}'","TO_DAYS(oldDate) = TO_DAYS(NOW())", "status = '已到'");
            $persionCollection[$v]['arrivalOut']        = $this->detail("custservice = '{$v}'","TO_DAYS(oldDate) = TO_DAYS(NOW())", "status = '未到'");
            $persionCollection[$v]['yesterArrival']     = $this->detail("custservice = '{$v}'","TO_DAYS(NOW()) - TO_DAYS(oldDate) = 1", "status = '已到'");
            $persionCollection[$v]['yesterArrivalOut']  = $this->detail("custservice = '{$v}'","TO_DAYS(NOW()) - TO_DAYS(oldDate) = 1", "status = '未到'");
            $persionCollection[$v]['thisArrival']       = $this->detail("custservice = '{$v}'","DATE_FORMAT(oldDate, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m')", "status = '已到'");
            $persionCollection[$v]['thisArrivalOut']    = $this->detail("custservice = '{$v}'","DATE_FORMAT(oldDate, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m')", "status = '未到'");
            $persionCollection[$v]['lastArrival']       = $this->detail("custservice = '{$v}'","PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'), DATE_FORMAT(oldDate,'%Y%m')) = 1", "status = '已到'");
            $persionCollection[$v]['lastArrivalOut']    = $this->detail("custservice = '{$v}'","PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'), DATE_FORMAT(oldDate,'%Y%m')) = 1", "status = '未到'");
            $persionCollection[$v]['arrivalTotal']      = $persionCollection[$v]['arrival'] + $persionCollection[$v]['arrivalOut'];
            $persionCollection[$v]['yestserTotal']      = $persionCollection[$v]['yesterArrival'] + $persionCollection[$v]['yesterArrivalOut'];
            $persionCollection[$v]['thisTotal']         = $persionCollection[$v]['thisArrival'] + $persionCollection[$v]['thisArrivalOut'];
            $persionCollection[$v]['lastTotal']         = $persionCollection[$v]['lastArrival'] + $persionCollection[$v]['lastArrivalOut'];
        }
        /* ******************************************************************************
         * ******************************************************************************
         *                                                                             **
         *  数据序列化, $persionCollection 这个二维数组下添加custserivce key!              **
         *  array_push()打入到的新的数组                                                 **
         *  return $persionCollList!                                                   **
         *                                                                             **
         * ******************************************************************************
         * */
        $persionKeys = array_keys($persionCollection);
        $persionCollList = array();
        for ($i = 0; $i < count($persionKeys); $i ++) {
            if ($persionKeys[$i] == $keyNames[$i]) {
                $persionCollection[$persionKeys[$i]]['custservice'] = $keyNames[$i];
                array_push($persionCollList, $persionCollection[$persionKeys[$i]]);
            }
        }
        return $persionCollList;
    }
    /*
     *  @@ custservice arrival filter
     *  @param null
     *  @return $custservice Type: array
     * */
    private function custservice () {;
        $tableName = $_COOKIE['tableName'];
        $collection = array();
        $collection['arrival']            = $this->detail("TO_DAYS(oldDate) = TO_DAYS(NOW())", "status = '已到'");
        $collection['arrivalOut']         = $this->detail("TO_DAYS(oldDate) = TO_DAYS(NOW())", "status != '已到'");
        $collection['yesterArrival']      = $this->detail("TO_DAYS(NOW()) - TO_DAYS(oldDate) = 1", "status = '已到'");
        $collection['yesterArrivalOut']   = $this->detail("TO_DAYS(NOW()) - TO_DAYS(oldDate) = 1", "status != '已到'");
        $collection['thisArrival']        = $this->detail("DATE_FORMAT(oldDate, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m')", "status = '已到'");
        $collection['thisArrivalOut']     = $this->detail("DATE_FORMAT(oldDate, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m')", "status != '已到'");
        $collection['lastArrival']        = $this->detail("PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'), DATE_FORMAT(oldDate,'%Y%m')) = 1", "status = '已到'");
        $collection['lastArrivalOut']     = $this->detail("PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'), DATE_FORMAT(oldDate,'%Y%m')) = 1", "status != '已到'");
        $collection['arrivalTotal']       = $collection['arrival'] + $collection['arrivalOut'];
        $collection['yesterTotal']        = $collection['yesterArrival'] + $collection['yesterArrivalOut'];
        $collection['thisTotal']          = $collection['thisArrival'] + $collection['thisArrivalOut'];
        $collection['lastTotal']          = $collection['lastArrival'] + $collection['lastArrivalOut'];
        return $collection;
    }
    /*
     *  @@monthdata
     *  @param null
     *  return   Type: json string
     * */
    public function monthdata () {
        $instance = M($_COOKIE['tableName']);
        $redis = $this->setCache();
        if ($redis->exists($_COOKIE['tableName'] . 'Month_echarst')) {
            $redis->expire($_COOKIE['tableName'] . 'Month_echarst', 1200);
        } else {
            $arrival = array();
            $arrival['reser']       = $instance->where("status = '预约未定' AND DATE_FORMAT(oldDate, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $arrival['advan']       = $instance->where("status = '等待' AND DATE_FORMAT(oldDate, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $arrival['arrival']     = $instance->where("status = '已到' AND DATE_FORMAT(oldDate, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $arrival['arrivalOut']  = $instance->where("status = '未到' AND DATE_FORMAT(oldDate, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $arrival['halfTotal']   = $instance->where("status = '全流失' AND DATE_FORMAT(oldDate, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $arrival['half']        = $instance->where("status = '半流失' AND DATE_FORMAT(oldDate, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $arrival['treat']       = $instance->where("status = '已诊治' AND DATE_FORMAT(oldDate, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')")->count();
            $redis->set($_COOKIE['tableName'] . 'Month_echarst', json_encode($arrival));
            $redis->expire($_COOKIE['tableName'] . 'Month_echarst', 1200);
        }
        $arrival = json_decode($redis->get($_COOKIE['tableName'] .  'Month_echarst'), true);
        $this->assign('echarts', $arrival);
        $this->display();
    }
    /*
     *  @@ detail function
     *  @param $array Type: array[0] condition, array[1] field.
     *  @return $allStatus Type: array
     * */
    private function detail ($time, $status, $persion = null) {
        $tableName = $_COOKIE['tableName'];
        if (is_null($persion)) {
            $allStatus = M($tableName)->where(array($time, $status))->count();
        } else {
            $allStatus = M($tableName)->where(array($time, $status, $persion))->count();
        }
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
        $user = M('management')->join('user')->where("user.username = management.pid")->select();
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
        $username = M('user')->where("id = {$_GET['id']}")->field('username')->select();
        $manageResolve = M('management')->where("pid = '{$username[0]['username']}'")->delete();
        $resovle = M('user')->where("id = {$_GET['id']}")->delete();
        if ($resovle) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *   @@userAdd
     *   @param null
     *   @return boolean Type: eval
     * */
    public function userAdd () {
        $management = json_decode($_GET['data'], true);
        $userList['password'] = MD5($management['password']);
        $userList['username'] = $management['username'];
        array_splice($management, 0, 2);
        $management['pid'] = $userList['username'];
        $managementResolve = M('management')->add($management);
        $resolve = M('user')->add($userList);
        if ($managementResolve && $resolve) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     *  @@userEdit
     *  @param null
     *  @return boolean Type: eval
     * */
    public function userEdit () {
        $management = json_decode($_GET['data'], true);
        $managementKey = array_keys($management);
        $fields = M('management')->getDbFields();
        $redundantKeys = array_diff($fields, $managementKey);
        while (list($k, $v) = each($redundantKeys)) $redundant[trim($v)] = '';
        $management = array_merge($redundant, $management);
        $addtime = date('Y-m-d H:i:s', time());
        unset($management['id']);
        $userList['password'] = MD5($management['password']);
        $userList['username'] = $management['username'];
        $userList['addtime'] = $addtime;
        $management['pid'] = $userList['username'];
        $management['addtime'] = $addtime;
        unset($management['username'], $management['password']); // Destruction of the element
        $username = M('user')->where("id = '{$_GET['id']}'")->field('username')->select(); // select user username.
        $resolve = M('user')->where("id = '{$_GET['id']}'")->save($userList); // save new username, password
        $managementUser = M('management')->where("pid = '{$username[0]['username']}'")->count();
        /* ******************************************************************************
         * ******************************************************************************
         *                                                                             **
         *    select user username to management table is have? return number.         **
         *  if have is add functoin.                                                   **
         *  else not have is save function.                                            **
         *                                                                             **
         * ******************************************************************************
         * */
        if (! $managementUser) {
            $managementResolve = M('management')->add($management);
        } else {
            $managementResolve = M('management')->where("pid = '{$username[0]['username']}'")->save($management);
        }
        if ($managementResolve && $resolve) {
            $this->ajaxReturn(true, 'eval');
        } else {
            $this->ajaxReturn(false, 'eval');
        }
    }
    /*
     * @@ resources Export page
     * */
    public function resources () {
        $this->display();
    }
    /*
     * @@ resources Export
     * @param null
     * @return Boolean Type: json
     * */
    public function resourcesCheck () {
        if (array_key_exists('null', $_GET)) return false;
        if (array_key_exists('date_min', $_GET) && array_key_exists('date_max', $_GET)) {
            $result = D('Collection')->resources($_GET);
            if (is_array($result) && isset($result)) {
                $hospitalVisitCount = $result[1];
                $hospitalVisit = $this->arraySplice($result[0]);
            }
        }
        $this->arrayRecursive($hospitalVisit, 'urlencode', true);
        $jsonVisit = urldecode(json_encode($hospitalVisit));
        $visitList = "{\"code\":0, \"msg\":\"\", \"count\": $hospitalVisitCount, \"data\": $jsonVisit}";
        $this->ajaxReturn(str_replace(array("\n", "\r"), '\n', $visitList), 'eval');
    }
    /*
     *  @@The personal data page
     *  @param null
     * */
    public function personal () {
        $this->display();
    }
    public function personalCheck () {
        $this->assign();
        // update my personal
    }
    /*
     * @@The login log page
     * @param null
     * */
    public function loginLog () {
        $this->display();
    }
    /*
     * @@The login log
     * @param null
     * @return  Type:json
     * */
    public function loginCheck () {
        $login_log = M('login_log')->select();
        if ($login_log) {
            $this->arrayRecursive($login_log, 'urlencode', true);
        } else {
            $this->ajaxReturn(false, 'eval');
        }
        $login_log = urldecode(json_encode($login_log));
        $loginList = "{\"code\":0, \"msg\":\"\", \"count\": 0, \"data\": $login_log}";
        $this->ajaxReturn($loginList, 'eval');
    }
    /*
     *  @@expansion connect redis.
     *  @param null.
     *  @return $redis. Type: instance
     * */
    private function setCache () {
        $redis = new \Redis();
        $redis->connect('211.149.x.x', 6379);
        $redis->auth('xxxxxx');
        $redis->select(1);
        if ($redis->ping() == "+PONG") return $redis;
        throw new Exception("Connection  Redis Failed...");
    }
    /*
     *  @@select data keys splice
     *  @param array
     *  @return array
     * */
    private function arraySplice ($hospitalVisit) {
        if (! is_array($hospitalVisit)) return false;
        // trim array \t
        for ($i = 0; $i < count($hospitalVisit); $i ++) {
            $diseasesTrim = trim($hospitalVisit[$i]['diseases']);
            $desc1 = trim($hospitalVisit[$i]['desc1']);
            unset($hospitalVisit[$i]['diseases']);
            unset($hospitalVisit[$i]['desc1']);
            $hospitalVisit[$i]['desc1'] = $desc1;
            $hospitalVisit[$i]['diseases'] = $diseasesTrim;
        }
        return $hospitalVisit;
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
              `name` varchar(15) NOT NULL DEFAULT '',
              `old` int NOT NULL DEFAULT 0,
              `phone` bigint(20) NOT NULL DEFAULT 0,
              `qq` bigint(20) NOT NULL DEFAULT 0,
              `diseases` varchar(30) NOT NULL,
              `fromAddress` varchar(15) NOT NULL,
              `switch` varchar(15) NOT NULL DEFAULT '外地',
              `sex` varchar(15) NOT NULL DEFAULT '男',
              `desc1` varchar(1500) NOT NULL DEFAULT '',
              `expert` varchar(10) NOT NULL,
              `oldDate` date NOT NULL,
              `desc2` varchar(300) NOT NULL DEFAULT '',
              `status` varchar(15) NOT NULL,
              `newDate` date NOT NULL,
              `currentTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
              `custService` varchar(30) NOT NULL,
              PRIMARY KEY(`id`),
              KEY `oldDate` (`oldDate`),
              KEY `status` (`status`)
              ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
sql;
        if (M()->query($sql)) return true;
        return false;
    }
}