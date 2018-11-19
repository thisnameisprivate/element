<?php

namespace Home\Controller;
use Org\Think\Date;
use Think\Controller;
class IndexController extends Controller {
    public function index () {
        $this->display();
    }
    public function login () {
        if ($_POST) {
            $user = M('user');
            $identifter = array($_POST['username'], md5($_POST['password']));
            $result = $user->where("username = '%s' and password = '%s'", $identifter)->select();
            if ($result) {
                setcookie('username', $_POST['username']);
                $this->loginLog($_POST, true);
                $this->success('login success', U('Admin/Index/index'));
            } else {
                $this->loginLog($_POST, false);
                $this->error('login failed.', U('Home/Index/index'));
            }
        }
    }
    /*
     *  @@login is success? write login_log table.
     *  @param Boolean
     * */
    private function loginLog ($requestVerify, $boolean) {
        $ips = get_client_ip();
        $ip = new \Org\Net\IpLocation('UTFWry.dat');
        $ares = $ip->getlocation($ips);
        $login_log['username']          = $requestVerify['username'];
        $login_log['password']          = md5($requestVerify['password']);
        $login_log['fromaddress']       = empty($ares['country']) ? '生产环境IP:localhost' : $ares['country']; // 归属地
        $login_log['ip']                = $ips;                          // ip地址
        $boolean ? $login_log['status'] = '登录成功' : $login_log['status'] = '登录失败';
        M('login_log')->add($login_log);
    }
}