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
    public function echarts () {
        $this->display();
    }
    public function visit () {
        $this->display();
    }
    /*
     *  @@ visit Data List.
     *  @param null
     *  @return $visitList Type: json.
     * */
    public function visitCheck () {
        $cookietable = cookie('tableName');
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
     *  @return boolean Type: json
     * */
    public function visitDel () {
        if (! is_numeric($_GET['id'])) return false;
        $cookietable = cookie('tableName');
        $resovle = M($cookietable)->where("id = {$_GET['id']}")->delete();
        if ($resovle) {
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
     *  @@disease data check
     *  @param null
     *  @return $diseasesList Type: json
     * */
    public function diseaseCheck () {
        $tableName = cookie('tableName');
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
        $diseasesData['tableName'] = cookie('tableName');
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
     *  @@ JsonString handle
     *  @param $array Type: array
     *  @param $function Type: string
     *  @param $apply_to_keys_also Type: boolean
     *  @return jsonString
     * */
    public function arrayRecursive(&$array, $function, $apply_to_keys_also = false) {
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
}