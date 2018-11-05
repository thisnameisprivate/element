<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>layout 后台大布局 - Layui</title>
    <link rel="stylesheet" href="/element/Public/statics/layui/css/layui.css">
    <style>
        .layui-body{height:100%; width:100%; overflow:hidden;}
        #iframe{height:105%; width:91.2%;}
        .layui-layout-admin .layui-header{background: #2F4056;}
        #loading{margin-left:200px;}
    </style>
</head>
<script type="text/javascript">
    // one loading time set default cookie.
    document.cookie = 'tableName=gyxhyynk';
    localStorage.setItem('userAcc', JSON.stringify(<?php echo ($userAcc); ?>));
</script>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">广元协和医院新媒体</div>
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item" onclick='iframeSetAttr("<?php echo U('Admin/Index/overView');?>")'><a href="javascript:;">首页</a></li>
            <li class="layui-nav-item"><a href="">商品管理</a></li>
            <li class="layui-nav-item"><a href="">用户</a></li>
            <li class="layui-nav-item">
                <a href="javascript:;">其它系统</a>
                <dl class="layui-nav-child">
                    <dd><a href="">邮件管理</a></dd>
                    <dd><a href="">消息管理</a></dd>
                    <dd><a href="">授权管理</a></dd>
                </dl>
            </li>
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

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <ul class="layui-nav layui-nav-tree"  lay-filter="test">
                <li class="layui-nav-item layui-nav-itemed">
                    <a class="" href="javascript:;"><span class="layui-icon layui-icon-list">&nbsp;&nbsp;</span>病人预约管理</a>
                    <dl class="layui-nav-child">
                        <dd><a  href="javascript:;" onclick="visit();">预约登记列表</a></dd>
                        <dd><a  href="javascript:;" onclick="detailReport();">客服明细报表</a></dd>
                        <dd><a  href="javascript:;" onclick="monthdata();">月趋势报表</a></dd>
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
                    <a href="javascript:;"><span class="layui-icon layui-icon-set-sm">&nbsp;&nbsp;</span>设置</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" onclick="doctor();">客服人员设置</a></dd>
                        <dd><a href="javascript:;" onclick="disease();">病患类型设置</a></dd>
                        <dd><a href="javascript:;" onclick="typesof();">媒体来源设置</a></dd>
                        <dd><a href="javascript:;" onclick="hospitalsList();">医院科室设置</a></dd>
                        <dd><a href="javascript:;" onclick="arrivalStatus();">来院状态设置</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;"><span class="layui-icon layui-icon-user">&nbsp;&nbsp;</span>我的资料</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">修改我的资料</a></dd>
                        <!--
                         <dd><a href="javascript:;">修改密码</a></dd>
                        <dd><a href="javascript:;">选项设置</a></dd>
                         -->
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;"><span class="layui-icon layui-icon-app">&nbsp;&nbsp;</span>系统管理</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" onclick="access();">用户管理</a></dd>
                        <!--
                         <dd><a href="javascript:;">医院列表</a></dd>
                        <dd><a href="javascript:;">通知列表</a></dd>
                         -->
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;"><span class="layui-icon layui-icon-log">&nbsp;&nbsp;</span>日志记录</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">操作日志</a></dd>
                        <dd><a href="javascript:;">登录错误日志</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>
    <div class="layui-progress" id="loading" style="dipslay:none">
        <div class="layui-progress-bar layui-bg-green" lay-percent="0%"></div>
    </div>
    <div class="layui-body">
        <iframe id="iframe" src="<?php echo U('Admin/Index/overView');?>" frameborder="0"></iframe>
    </div>
</div>
<div style="position:fixed; bottom:0px; left:200px; z-index:999; font-size:12px; font-weight:600;">
    <!-- 底部固定区域 -->
    <a href="javascript:;" title="发布日期: 2018/10/1日:)"><span class="layui-icon layui-icon-website layui-anim layui-anim-fadein layui-anim-loop"></span>&nbsp;&nbsp;&nbsp;广元协和医院预约回访管理系统 ©</a>
</div>
</body>
<script src="/element/Public/statics/layui/layui.js"></script>
<script type="text/javascript">
    layui.use(['element', 'layer', 'form'], () => {
        const iframe = document.getElementById('iframe');
        var element = layui.element;
        var $ = layui.jquery;
        var userAcc = JSON.parse(localStorage.getItem('userAcc'));
        // 医院列表下拉框渲染
        readyHospital = tableName => {
            var ification = document.getElementById('classification');
            ification.innerHTML = tableName.innerText + "<span class='layui-nav-more'></span>";
            console.log(tableName.getAttribute('tablename'));
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
    });
</script>
</html>