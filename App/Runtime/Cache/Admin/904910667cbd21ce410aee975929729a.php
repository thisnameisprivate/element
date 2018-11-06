<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/element/Public/statics/layui/css/layui.css">
    <style>
        .container{position:relative; width:900px; height: 500px; top:0; left:0; bottom:0; right:0; margin:auto; padding-top: 50px;}
        body{overflow-y: scroll; margin-top:20px;}
    </style>
</head>
<body>
<table id="container" lay-filter="edittable"></table>
<div class="layui-container" id="userAdd" style="display:none;">
    <div class="container">
        <form class="layui-form" lay-filter="formAdd">
            <hr class="layui-bg-green">
            <div>
                <span class="layui-badge layui-bg-green">用户信息</span>
                <span class="layui-badge-dot"></span>
                <span class="layui-badge-dot layui-bg-orange"></span>
                <span class="layui-badge-dot layui-bg-green"></span>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">账号</label>
                <div class="layui-input-inline">
                    <input type="text" name="username" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入用户名~">
                </div>
                <label class="layui-form-label">密码</label>
                <div class="layui-input-inline">
                    <input type="text" name="password" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入密码~">
                </div>
            </div>
            <hr class="layui-bg-green">
            <div>
                <span class="layui-badge">用户权限</span>
                <span class="layui-badge-dot"></span>
                <span class="layui-badge-dot layui-bg-orange"></span>
                <span class="layui-badge-dot layui-bg-green"></span>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">预约管理</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="resready" title="可读">
                    <input type="checkbox" name="reswrite" title="可写">
                </div>
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="resupdate" title="修改">
                    <input type="checkbox" name="resdelete" title="删除">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">挂号管理</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="sysready" title="可读">
                    <input type="checkbox" name="syswrite" title="可写">
                </div>
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="sysupdate" title="修改">
                    <input type="checkbox" name="sysdelete" title="删除">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">数据列表</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="listready" title="可读">
                    <input type="checkbox" name="listwrite" title="可写">
                </div>
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="listupdate" title="修改">
                    <input type="checkbox" name="listdelete" title="删除">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">设置</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="setready" title="可读">
                    <input type="checkbox" name="setwrite" title="可写">
                </div>
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="setupdate" title="修改">
                    <input type="checkbox" name="setdelete" title="删除">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">我的资料</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="myready" title="可读">
                    <input type="checkbox" name="mywrite" title="可写">
                </div>
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="myupdate" title="修改">
                    <input type="checkbox" name="mydelete" title="删除">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">系统管理</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="manageready" title="可读">
                    <input type="checkbox" name="managewrite" title="可写">
                </div>
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="manageupdate" title="修改">
                    <input type="checkbox" name="managedelete" title="删除">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">日志记录</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="logready" title="可读">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="" lay-filter="formadd">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- edit -->
<div class="layui-container" id="userEdit" style="display:none;">
    <div class="container">
        <p style="display:none" id="idValue"></p>
        <form class="layui-form" lay-filter="formedit">
            <hr class="layui-bg-green">
            <div>
                <span class="layui-badge layui-bg-green">用户信息</span>
                <span class="layui-badge-dot"></span>
                <span class="layui-badge-dot layui-bg-orange"></span>
                <span class="layui-badge-dot layui-bg-green"></span>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">账号</label>
                <div class="layui-input-inline">
                    <input type="text" name="username" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入用户名~">
                </div>
                <label class="layui-form-label">密码</label>
                <div class="layui-input-inline">
                    <input type="text" name="password" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入密码~">
                </div>
            </div>
            <hr class="layui-bg-green">
            <div>
                <span class="layui-badge">用户权限</span>
                <span class="layui-badge-dot"></span>
                <span class="layui-badge-dot layui-bg-orange"></span>
                <span class="layui-badge-dot layui-bg-green"></span>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">预约管理</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="resready" title="可读">
                    <input type="checkbox" name="reswrite" title="可写">
                </div>
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="resupdate" title="修改">
                    <input type="checkbox" name="resdelete" title="删除">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">挂号管理</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="sysready" title="可读">
                    <input type="checkbox" name="syswrite" title="可写">
                </div>
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="sysupdate" title="修改">
                    <input type="checkbox" name="sysdelete" title="删除">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">数据列表</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="listready" title="可读">
                    <input type="checkbox" name="listwrite" title="可写">
                </div>
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="listupdate" title="修改">
                    <input type="checkbox" name="listdelete" title="删除">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">设置</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="setready" title="可读">
                    <input type="checkbox" name="setwrite" title="可写">
                </div>
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="setupdate" title="修改">
                    <input type="checkbox" name="setdelete" title="删除">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">我的资料</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="myready" title="可读">
                    <input type="checkbox" name="mywrite" title="可写">
                </div>
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="myupdate" title="修改">
                    <input type="checkbox" name="mydelete" title="删除">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">系统管理</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="manageready" title="可读">
                    <input type="checkbox" name="managewrite" title="可写">
                </div>
                <label class="layui-form-label"></label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="manageupdate" title="修改">
                    <input type="checkbox" name="managedelete" title="删除">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">日志记录</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="logready" title="可读">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="" lay-filter="formedit">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
<script src="/element/Public/statics/layui/layui.js"></script>
<script type="text/html" id="toolbaradd">
    <div class="layui-btn-container">
        <button class="layui-btn layui-btn-sm layui-icon layui-icon-add-1" lay-event="add">&nbsp;添加</button>
        <button class="layui-btn layui-btn-sm layui-icon layui-icon-refresh" lay-event="reload">&nbsp;刷新</button>
    </div>
</script>
<script type="text/html" id="bar">
    <button class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon"></i></button>
    <button class="layui-btn layui-btn-xs" lay-event="del"><i class="layui-icon"></i></button>
</script>
<script>
    layui.use(['laydate', 'table', 'layer', 'laypage', 'form'], () => {
        var table = layui.table;
        var laydate = layui.laydate;
        var layer = layui.layer;
        var form = layui.form;
        var laypage = layui.laypage;
        var $ = layui.jquery;
        var userAcc = JSON.parse(localStorage.getItem('userAcc'));
        var tableIns = table.render({
            text: {
                none: '暂无相关数据',
            },
            initSort: {
                field: 'id',
                type: 'desc',
            },
            elem: '#container',
            toolbar: '#toolbaradd',
            url: "<?php echo U('Admin/Index/accessCheck');?>",
            height:'full-200',
            cellMinWidth:50,
            loading: true,
            size: 'sm',
            cols: [[
                    {align: 'center', title: '', colspan: 2},
                    {align: 'center', title: '预约管理', colspan: 4},
                    {align: 'center', title: '挂号管理', colspan: 4},
                    {align: 'center', title: '数据列表', colspan: 4},
                    {align: 'center', title: '设置', colspan: 4},
                    {align: 'center', title: '我的资料', colspan: 4},
                    {align: 'center', title: '系统管理', colspan: 4},
                    {align: 'center', title: '日志记录', colspan: 3},
                    {aling: 'center', title: '', clospan:2}
                ],[
                {field: 'id', title: 'No .', width: 60, sort: true},
                {field: 'pid', title: '用户名', width: '5%'},
                {field: 'resready', title: '读'},
                {field: 'reswrite', title: '写'},
                {field: 'resupdate', title: '改'},
                {field: 'resdelete', title: '删'},
                {field: 'sysready', title: '读'},
                {field: 'syswrite', title: '写'},
                {field: 'sysupdate', title: '改'},
                {field: 'sysdelete', title: '删'},
                {field: 'listready', title: '读'},
                {field: 'listwrite', title: '写'},
                {field: 'listupdate', title: '改'},
                {field: 'listdelete', title: '删'},
                {field: 'setready', title: '读'},
                {field: 'setwrite', title: '写'},
                {field: 'setupdate', title: '改'},
                {field: 'setdelete', title: '删'},
                {field: 'myready', title: '读'},
                {field: 'mywrite', title: '写'},
                {field: 'myupdate', title: '改'},
                {field: 'mydelete', title: '删'},
                {field: 'manageready', title: '读'},
                {field: 'managewrite', title: '写'},
                {field: 'manageupdate', title: '改'},
                {field: 'managedelete', title: '删'},
                {field: 'logready', title: '读'},
                {fixed: 'right', title: '操作', width:100, align:'center', toolbar: '#bar'},
            ]],
            id: 'edittable',
            done: function () {

            }
        });
        table.on('tool(edittable)', obj => {
            var data = obj.data;
            var layEvent = obj.event;
            var tr = obj.tr;
            if (layEvent === 'del') { // delete tool data
                if (! Boolean(userAcc.managedelete)) { layer.msg('权限不足', {icon:5}); return false; }
                layer.confirm('【 ' + data.name + ' 】are you soure delete ?', (index) => {
                    var client = new XMLHttpRequest();
                    client.open("GET", "<?php echo U('Admin/Index/userDel/id/" + parseInt(data.id) + "');?>");
                    client.send();
                    client.onreadystatechange = () => {
                        if (client.readyState === 4 && client.status === 200) {
                            if (client.response == 1) {
                                layer.msg('delete success', {icon: 6});
                                obj.del();
                                layer.close(index);
                            } else {
                                layer.msg('delete fialed', {icon: 5});
                            }
                        }
                    }
                });
            } else if (layEvent === 'edit') { // edit tool data
                if (! Boolean(userAcc.manageupdate)) { layer.msg('权限不足', {icon:5}); return false; }
                document.getElementById('idValue').innerHTML = data.id;
                layer.open({
                    type: 1,
                    title: '编辑信息',
                    area: ['65%', '75%'],
                    content: document.getElementById('userEdit').innerHTML,
                });
                form.render();
                setFormValue(data);
                form.on('submit(formedit)', data => {
                    var id = document.getElementById('idValue').innerHTML;
                    var client = new XMLHttpRequest();
                    client.open('GET', "<?php echo U('Admin/Index/userEdit/id/" + parseInt(id) + "/data/"+ JSON.stringify(data.field) +"');?>");
                    client.send();
                    client.onreadystatechange = () => {
                        if (client.readyState === 4 && client.status === 200) {
                            if (client.response == 1) {
                                layer.msg('add success', {icon: 6});
                                layer.closeAll('page');
                                tableIns.reload();
                            } else {
                                layer.msg('add failed', {icon: 5});
                            }
                        }
                    }
                    return false;
                });
            }
        });
        table.on('toolbar(edittable)', obj => {
            var checkStatus = table.checkStatus(obj.config.id);
            if (obj.event === 'reload') {
                tableIns.reload();
            } else if (obj.event === 'add') {
                if (! Boolean(userAcc.managewrite)) { layer.msg('权限不足', {icon:5}); return false; }
                layer.open({
                    type: 1,
                    title: '新增信息',
                    area: ['65%', '75%'],
                    content: document.getElementById('userAdd').innerHTML,
                });
                form.render();
                form.on('submit(formadd)', data => {
                    var client = new XMLHttpRequest();
                    client.open('GET', "<?php echo U('Admin/Index/userAdd/data/"+ JSON.stringify(data.field) +"');?>");
                    client.send();
                    client.onreadystatechange = () => {
                        if (client.readyState === 4 && client.status === 200) {
                            if (client.response == 1) {
                                layer.msg('add success', {icon: 6});
                                layer.closeAll('page');
                                tableIns.reload();
                            } else {
                                layer.msg('add failed', {icon: 5});
                            }
                        }
                    }
                    return false;
                });
            }
        });
        /* edit 渲染表单 */
        setFormValue = data => {
            form.val('formedit', {
                'username': data.pid,
                'resready': data.resready,
                'reswrite': data.reswrite,
                'resupdate': data.resupdate,
                'resdelete': data.resdelete,
                'sysready': data.sysready,
                'syswrite': data.syswrite,
                'sysupdate': data.sysupdate,
                'sysdelete': data.sysdelete,
                'listready': data.listready,
                'listwrite': data.listwrite,
                'listupdate': data.listupdate,
                'listdelete': data.listdelete,
                'setready': data.setready,
                'setwrite': data.setwrite,
                'setupdate': data.setupdate,
                'setdelete': data.setdelete,
                'myready': data.myready,
                'mywrite': data.mywrite,
                'myupdate': data.myupdate,
                'mydelete': data.mydelete,
                'manageready': data.manageready,
                'managewrite': data.managewrite,
                'manageupdate': data.manageupdate,
                'managedelete': data.managedelete,
                'logready': data.logready,
            })
        }
    })
</script>
</html>