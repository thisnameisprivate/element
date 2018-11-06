<?php

namespace Home\Controller;
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
                $this->success('login success', U('Admin/Index/index'));
            } else {
                $this->error('login failed.', U('Home/Index/index'));
            }
        }
    }
}