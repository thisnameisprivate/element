<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>广元协和医院网络部</title>
    <link rel="stylesheet" href="/element/Public/statics/layui/css/layui.css">
    <style>
        .layui-body{height:100%; width:100%; overflow:hidden;}
        #iframe{height:105%; width:91.2%;}
        .layui-layout-admin .layui-header{background: #2F4056;}
        #loading{margin-left:200px;}
        .title-icon-size{font-size:1.2rem;}
        /* 通讯图标样式 */
        .container{height:50px; width:50px; position:fixed; bottom:50px; right:10px; background-color:#5FB878; text-align:center; border-radius:5px; cursor:pointer; z-index: 999;}
        .container span{font-size:40px; color:#ffffff; line-height:50px;}
        .container:hover{background-color:#9F9F9F;}
    </style>
</head>
<script type="text/javascript">
    // get cookie.
    function getCookie (cookieName) {
        var arrCookie = document.cookie.split('; ');
        for (var i = 0; i < arrCookie.length; i ++) {
            var arr = arrCookie[i].split('=');
            if (cookieName == arr[0]) {
                return arr[1];
            }
        }
        return '';
    }
    // delete cookie
    function delCookie (cookieName) {
        var time = new Date();
        time.setTime(time.getTime() + 1);
        var expires = "expires=" + time.toGMTString() + ";path=/";
        document.cookie = cookieName + "=;" + expires;
    }

    // one loading time set default cookie.
    document.cookie = 'tableName=gyxhyynk';
    localStorage.setItem('userAcc', JSON.stringify(<?php echo ($userAcc); ?>));
    // fread current cookie username
    var username = <?php echo ($userAcc); ?>.pid;
</script>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">广元协和医院咨询1.0</div>
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item kit-side-fold"><a href="javascript:;"><sapn class="layui-icon layui-icon-shrink-right title-icon-size" tips="侧边栏伸缩"></sapn></a></li>
            <li class="layui-nav-item" onclick='iframeSetAttr("<?php echo U('Admin/Index/overView');?>")'><a href="javascript:;"><span class="layui-icon layui-icon-home title-icon-size" tips="首页"></span></a></li>
            <li class="layui-nav-item">
                <a href="javascript:;"><span class="layui-icon layui-icon-username title-icon-size" tips="在线用户"></span></a>
                <dl class="layui-nav-child" id="userList">
                    <!--<dd><span class="layui-badge-dot layui-bg-green"></span><a href="">admin</a></dd>-->
                </dl>
            </li>
            <!--<li class="layui-nav-item">-->
            <!--<a href="javascript:;"><a href="javascript:;"><span class="layui-icon layui-icon-component title-icon-size" tips="其他系统"></span></a></a>-->
            <!--<dl class="layui-nav-child">-->
            <!--<dd><a href="">邮件管理</a></dd>-->
            <!--<dd><a href="">消息管理</a></dd>-->
            <!--<dd><a href="">授权管理</a></dd>-->
            <!--</dl>-->
            <!--</li>-->
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="javascript:;" class="layui-anim layui-anim-up layui-this" id="classification">广元协和医院男科</a>
                <dl class="layui-nav-child">
                    <?php if(is_array($hospitals)): foreach($hospitals as $index=>$vo): ?><dd class="layui-anim layui-anim-scaleSpring"><a href="javascript:;" onclick="readyHospital(this);" tablename="<?php echo ($vo['tableName']); ?>"><?php echo ($vo['hospital']); ?></a></dd><?php endforeach; endif; ?>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="">注销</a></li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black" id="layui-side">
        <div class="layui-side-scroll">
            <ul class="layui-nav layui-nav-tree"  lay-filter="test">
                <li class="layui-nav-item layui-nav-itemed">
                    <a href="javascript:;"><span class="layui-icon layui-icon-component">&nbsp;&nbsp;</span><span class="slide-font">病人预约管理</span></a>
                    <dl class="layui-nav-child">
                        <dd><a  href="javascript:;" onclick="visit();"><span class="layui-icon layui-icon-edit">&nbsp;&nbsp;</span><span class="slide-font">预约登记列表</span></a></dd>
                        <dd><a  href="javascript:;" onclick="detailReport();"><span class="layui-icon layui-icon-survey">&nbsp;&nbsp;</span><span class="slide-font">客服明细报表</span></a></dd>
                        <dd><a  href="javascript:;" onclick="monthdata();"><span class="layui-icon layui-icon-console">&nbsp;&nbsp;</span><span class="slide-font">月趋势报表</span></a></dd>
                        <!--
                         <dd><a  href="javascript:;">数据横向对比</a></dd>
                         -->
                    </dl>
                </li>
                <!--
                访客数据统计功能
                 <li class="layui-nav-item">
                  <a href="javascript:;"><span class="layui-icon layui-icon-chart">&nbsp;&nbsp;</span>访客数据统计</a>
                  <dl class="layui-nav-child">
                    <dd><a href="javascript:;" onclick="monthData(this);">数据明细（网络）</a></dd>
                    <dd><a href="javascript:;" onclick="monthData(this);">医院项目设置（网络）</a></dd>
                    <dd><a href="javascript:;" onclick="monthData(this);">数据明细（电话）</a></dd>
                    <dd><a href="javascript:;" onclick="monthData(this);">医院项目设置（电话）</a></dd>
                  </dl>
                </li>
                 -->
                <!--
                <li class="layui-nav-item">
                    <a href="javascript:;"><span class="layui-icon layui-icon-chart-screen">&nbsp;&nbsp;</span>网站挂号管理</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">网站挂号列表</a></dd>
                        <dd><a href="javascript:;">网站挂号设置</a></dd>
                    </dl>
                </li>
                -->
                <!--
                 <li class="layui-nav-item">
                    <a href="javascript:;"><span class="layui-icon layui-icon-form">&nbsp;&nbsp;</span>数据列表</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">总体报表</a></dd>
                        <dd><a href="javascript:;">性别</a></dd>
                        <dd><a href="javascript:;">病患类型</a></dd>
                        <dd><a href="javascript:;">媒体来源</a></dd>
                        <dd><a href="javascript:;">来院状态</a></dd>
                        <dd><a href="javascript:;">接待医生</a></dd>
                        <dd><a href="javascript:;">客服</a></dd>
                    </dl>
                </li>
                 -->
                <li class="layui-nav-item">
                    <a href="javascript:;"><span class="layui-icon layui-icon-set-sm">&nbsp;&nbsp;</span><span class="slide-font">设置</span></a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" onclick="doctor();"><span class="layui-icon layui-icon-dialogue">&nbsp;&nbsp;</span><span class="slide-font">客服人员设置</span></a></dd>
                        <dd><a href="javascript:;" onclick="disease();"><span class="layui-icon layui-icon-survey">&nbsp;&nbsp;</span><span class="slide-font">病患类型设置</span></a></dd>
                        <dd><a href="javascript:;" onclick="typesof();"><span class="layui-icon layui-icon-survey">&nbsp;&nbsp;</span><span class="slide-font">媒体来源设置</span></a></dd>
                        <dd><a href="javascript:;" onclick="hospitalsList();"><span class="layui-icon layui-icon-survey">&nbsp;&nbsp;</span><span class="slide-font">医院科室设置</span></a></dd>
                        <dd><a href="javascript:;" onclick="arrivalStatus();"><span class="layui-icon layui-icon-survey">&nbsp;&nbsp;</span><span class="slide-font">来院状态设置</span></a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;"><span class="layui-icon layui-icon-user">&nbsp;&nbsp;</span><span class="slide-font">我的资料</span></a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" onclick="personal();"><span class="layui-icon layui-icon-edit">&nbsp;&nbsp;</span><span class="slide-font">修改我的资料</span></a></dd>
                        <!--
                         <dd><a href="javascript:;">修改密码</a></dd>
                        <dd><a href="javascript:;">选项设置</a></dd>
                         -->
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;"><span class="layui-icon layui-icon-auz">&nbsp;&nbsp;</span><span class="slide-font">系统管理</span></a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" onclick="access();"><span class="layui-icon layui-icon-user">&nbsp;&nbsp;</span><span class="slide-font">用户管理</span></a></dd>
                        <dd><a href="javascript:;" onclick="resources();"><span class="layui-icon layui-icon-user">&nbsp;&nbsp;</span><span class="slide-font">数据导出</span></a></dd>
                        <!--
                         <dd><a href="javascript:;">医院列表</a></dd>
                         <dd><a href="javascript:;">通知列表</a></dd>
                         -->
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;"><span class="layui-icon layui-icon-log">&nbsp;&nbsp;</span><span class="slide-font">日志记录</span></a>
                    <dl class="layui-nav-child">
                        <!--<dd><a href="javascript:;"><span class="layui-icon layui-icon-survey">&nbsp;&nbsp;</span><span class="slide-font">操作日志</span></a></dd>-->
                        <dd><a href="javascript:;" onclick="loginLog();"><span class="layui-icon layui-icon-survey">&nbsp;&nbsp;</span><span class="slide-font">登录错误日志</span></a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>
    <div class="layui-progress" id="loading" style="dipslay:none">
        <div class="layui-progress-bar layui-bg-green" lay-percent="0%"></div>
    </div>
    <div class="layui-body" id="layuibody">
        <iframe id="iframe" src="<?php echo U('Admin/Index/overView');?>" frameborder="0"></iframe>
    </div>
</div>
<div class="container">
    <span id="communication" class="layui-icon layui-icon-chat"></span>
</div>
<div id="bottom-title" style="position:fixed; bottom:0px; left:200px; z-index:999; font-size:12px; font-weight:600;">
    <!-- 底部固定区域 -->
    <a href="javascript:;" title="发布日期: 2018/10/1日:)"><span class="layui-icon layui-icon-website layui-anim layui-anim-fadein layui-anim-loop"></span>&nbsp;&nbsp;&nbsp;广元协和医院预约回访管理系统 ©</a>
</div>
</body>
<div id="worker-conatiner">
    <div class=""></div>
</div>
<script src="/element/Public/statics/layui/layui.js"></script>
<script type="text/javascript">
    layui.use(['element', 'layer', 'form'], () => {
        const iframe = document.getElementById('iframe');
        var element = layui.element;
        var layer = layui.layer;
        var $ = layui.jquery;
        var userAcc = JSON.parse(localStorage.getItem('userAcc'));
        // 医院列表下拉框渲染
        readyHospital = tableName => {
            var ification = document.getElementById('classification');
            ification.innerHTML = tableName.innerText + "<span class='layui-nav-more'></span>";
            document.cookie = 'tableName=' + tableName.getAttribute('tablename');
            iframeSetAttr("<?php echo U('Admin/Index/overView');?>");
        }
        // function request.
        visit = () => {
            if (! Boolean(userAcc.resready)) { layer.msg("权限不足", {icon: 5}); return false; }
            iframeSetAttr("<?php echo U('Admin/Index/visit');?>")
        }
        hospitalsList = () => {
            if (! Boolean(userAcc.setready)) { layer.msg("权限不足", {icon: 5}); return false; }
            iframeSetAttr("<?php echo U('Admin/Index/hospitalsList');?>")
        }
        disease = () => {
            if (! Boolean(userAcc.setready)) { layer.msg("权限不足", {icon: 5}); return false; }
            iframeSetAttr("<?php echo U('Admin/Index/disease');?>")
        }
        typesof = () => {
            if (! Boolean(userAcc.setready)) { layer.msg("权限不足", {icon: 5}); return false; }
            iframeSetAttr("<?php echo U('Admin/Index/typesof');?>")
        }
        doctor = () => {
            if (! Boolean(userAcc.setready)) { layer.msg("权限不足", {icon: 5}); return false; }
            iframeSetAttr("<?php echo U('Admin/Index/doctor');?>")
        }
        arrivalStatus = () => {
            if (! Boolean(userAcc.setready)) { layer.msg("权限不足", {icon: 5}); return false; }
            iframeSetAttr("<?php echo U('Admin/Index/arrivalStatus');?>")
        }
        detailReport = () => {
            if (! Boolean(userAcc.resready)) { layer.msg("权限不足", {icon: 5}); return false; }
            iframeSetAttr("<?php echo U('Admin/Index/detailReport');?>")
        }
        monthdata = () => {
            if (! Boolean(userAcc.resready)) { layer.msg("权限不足", {icon: 5}); return false; }
            iframeSetAttr("<?php echo U('Admin/Index/monthdata');?>")
        }
        access = () => {
            if (! Boolean(userAcc.manageready)) { layer.msg("权限不足", {icon: 5}); return false; }
            iframeSetAttr("<?php echo U('Admin/Index/access');?>")
        }
        resources = () => {
            if (! Boolean(userAcc.manageready)) { layer.msg("权限不足", {icon: 5}); return false; }
            iframeSetAttr("<?php echo U('Admin/Index/resources');?>")
        }
        personal = () => {
            if (! Boolean(userAcc.myready)) { layer.msg("权限不足", {icon: 5}); return false };
            iframeSetAttr("<?php echo U('Admin/Index/personal');?>");
        }
        loginLog = () => {
            if (! Boolean(userAcc.logready)) { layer.msg("权限不足", {icon: 5}); return false; }
            iframeSetAttr("<?php echo U('Admin/Index/loginLog');?>")
        }
        iframeSetAttr = (url) => {
            loadingStart();
            iframe.setAttribute('src', url);
        }
        //  Request function
        Request = (url) => {
            var Request = new XMLHttpRequest();
            Request.open('GET', url);
            Request.send();
            Request.onreadystatechange = () => {
                if (Request.readyState === 4 && Request.status === 200) {
                    $('#iframe').setAttribute('src', url);
                }
            }
        }
        // Projress loading ...
        loadingStart = () => {
            var projress = $('#loading').show().children();
            var promise = new Promise(resolve => {
                projress.animate({width: '33.8%'}, 100, () => {
                    setTimeout(() => {
                        resolve();
                    }, 100)
                });
            }).then(() => {
                return new Promise(resolve => {
                    projress.animate({width: '66.8%'}, 100, () => {
                        setTimeout(() => {
                            resolve();
                        }, 100)
                    });
                });
            }).then(() => {
                return new Promise(resolve => {
                    projress.animate({width: '98.8%'}, 150, () => {
                        setTimeout(() => {
                            resolve();
                        }, 150)
                    });
                });
            }).then(resolve => {
                projress.animate({width: '0%'});
                $('#loading').hide(200);
            });
        }
        // 侧边栏伸缩
        var isShow = true;
        $('.kit-side-fold').click(function () {
            // console.log($('.layui-nav-tree').children().children().children('span.layui-nav-more'));
            if (isShow) {
                $('#layui-side').animate({width: '60px'}, 100);
                $('#layuibody').animate({left: '60px'}, 100).children().css('width', '100%');
                $('#bottom-title').animate({left: '60px'}, 100);
                $('.layui-nav-tree').animate({width: '75px'}, function () {
                    $(this).children().children().children('span.layui-nav-more').hide();
                });
                $(this).children().children()
                    .removeClass('layui-icon-shrink-right')
                    .addClass('layui-icon-spread-left');
                $('.slide-font').each(function () {
                    $(this).hide(100).prev().css('font-size', '20px');
                })
                // 鼠标悬停提示效果
                $('.slide-font').parent().on({mouseover: function () {
                        $(this).each(function () {
                            layer.tips($(this).text(), $(this), {
                                tips: 2,
                                time: 1000,
                            });
                        });
                    }}, {mouseout: function () {
                        layer.close('tips');
                    }});
                isShow = false;
            } else {
                $('#layui-side').animate({width: '200px'}, 150);
                $('#layuibody').animate({left: '200px'}, 150).children().css('width', '91.2%');
                $('#bottom-title').animate({left: '200px'}, 100);
                $(this).children().children()
                    .removeClass('layui-icon-spread-left')
                    .addClass('layui-icon-shrink-right');
                $('.layui-nav-tree').animate({width: '200px'}, function () {
                    $(this).children().children().children('span.layui-nav-more').show();
                });
                isShow = true;
                $('.slide-font').each(function () {
                    $(this).show(100).prev().css('font-size', '14px');
                })
            }
        })
        $('.title-icon-size').on({mouseover: function () {
                $(this).each(function () {
                    layer.tips($(this).attr('tips'), $(this), {
                        tips: 3,
                        time: 1000,
                    });
                });
            }}, {mouseout: function () {
                layer.close('tips');
            }});
        // 通讯图标动画
        $('#communication').mouseover(function () {
            $(this).addClass('layui-anim layui-anim-rotate');
        }).mouseout(function () {
            $(this).removeClass('layui-anim layui-anim-rotate');
        });
        // 通讯弹出层
        $('#communication').click(function () {
            layer.msg('开发中...', {icon: 6});
        });
        // $('#userList').html("<dd><span class=\"layui-badge-dot layui-bg-green\"></span><a href=\"\">" + username + "</a></dd>");
        /**************************************************************************************************************
         *      ##         ##  ##########   ########\  #########    ####      ##   ##  ##########  ##########         **
         *     ##   ##    ##  ##           ##      ## ##         ##     ##   ##  ##   ##              ##              **
         *    ##  ## ##  ##  ##########   #########  #########  ##      ##  ## ##    ##########      ##               **
         *   ## ##   ## ##  ##           ##       ##       ##    ##    ##  ##  ##   ##              ##                **
         *  ###       ###  ###########  #########/  ########      ####    ##    ## ###########     ##                 **
         *                                                                                                            **
         ***************************************************************************************************************
         ***************************************************************************************************************
         * */
        var socket = new WebSocket('ws://211.149.233.203:2000');
        // 多客户端协议
        function say_to_all (content) {
            socket.addEventListener('open', function () {
                var message = {"type": "say_to_all", "content": content};
                socket.send(JSON.stringify(message));
            })
        }
        // 单对单通讯协议
        function say_to_one (content) {
            var message = {"type": "say_to_one", "content": content};
            socket.send(JSON.stringify(message));
        }
        socket.onopen = () => {
            var data = username;
            var message = {"type": "say_to_all", "content": data, "init": "login"};
            socket.send(JSON.stringify(message));
        };
        // say_to_all("这是第一条测试群发消息，你好， workerman通讯框架!");
        socket.onmessage = (event) => {
            var str = event.data;
            if (str.substr(0, 1) == '{' && JSON.parse(event.data).type == 'init') {
                var client_id = JSON.parse(event.data).client_id;
                delCookie(username);
                document.cookie =  username + "=" + client_id;
                return;
            } else {
                // 根据 | 截取字符串转换为数组遍历到Html.
                var userList = str.substr(0, str.indexOf('|'));
                var userArr  = userList.split(',');
                // 渲染 html
                for (var i = 0; i < userArr.length; i ++) {
                    $('#userList').html("<dd><span class=\"layui-badge-dot layui-bg-green\"></span><a href=\"\">"+ userArr[i] +"</a></dd>");
                }
            }
        }
    });
</script>
</html>