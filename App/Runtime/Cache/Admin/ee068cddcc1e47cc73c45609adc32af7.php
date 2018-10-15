<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/element/Public/statics/layui/css/layui.css">
    <style>
        .container{position:relative; width:900px; height: 500px; top:0; left:0; bottom:0; right:0; margin:auto; padding-top: 50px;}
        body{overflow-y: scroll; margin-top:20px;}
    </style>
    <title>visit</title>
</head>
<body>
<div class="layui-inline">
    <form class="layui-form">
        <input class="layui-input" name="search" id="search" required lay-verify="required" placeholder="姓名/客户电话" autocomplete="off">
    </form>
</div>
<button id="searchbtn" class="layui-btn" data-type="reload">搜索</button>
<table id="container" lay-filter="edittable"></table>
<div class="layui-container" id="layerpopEdit" style="display:none;">
    <div class="container">
        <p style="display:none" id="idValue"></p>
        <form class="layui-form" lay-filter="formedit">
            <div class="layui-form-item">
                <label class="layui-form-label">回访目标</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" lay-verify="required" placeholder="请输入病人名字或昵称~" autocomplete="off" class="layui-input">
                </div>
                <label class="layui-form-label">回访电话</label>
                <div class="layui-input-inline">
                    <input type="text" name="clientPhone" lay-verify="phone" placeholder="请输入病人联系方式~" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">年龄</label>
                <div class="layui-input-inline">
                    <input type="number" name="old" lay-verify="required" placeholder="请输入病人年龄~" autocomplete="off" class="layui-input">
                </div>
                <label class="layui-form-label">专家号</label>
                <div class="layui-input-inline">
                    <input type="number" name="old" lay-verify="required" placeholder="请输入病人年龄~" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">预约时间</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" id="date">
                </div>
                <label class="layui-form-label">客服姓名</label>
                <div class="layui-input-inline">
                    <select name="name" lay-verify="required">
                        <?php if(is_array($custservices)): foreach($custservices as $index=>$vo): ?><option value="<?php echo ($index); ?>"><?php echo ($vo['custservice']); ?></option><?php endforeach; endif; ?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">回访状态</label>
                <div class="layui-input-inline">
                    <div class="layui-input-inline">
                        <select name="visitStatus" lay-verify="required">
                            <?php if(is_array($visitstatusValue)): foreach($visitstatusValue as $index=>$vo): ?><option value="<?php echo ($index); ?>"><?php echo ($vo['visitstatus']); ?></option><?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
                <label class="layui-form-label">来院状态</label>
                <div class="layui-input-inline">
                    <div class="layui-input-inline">
                        <select name="status" lay-verify="required">
                            <?php if(is_array($statusValue)): foreach($statusValue as $index=>$vo): ?><option value="<?php echo ($index); ?>"><?php echo ($vo['status']); ?></option><?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">消费金额</label>
                <div class="layui-input-inline">
                    <input type="text" name="money" placeholder="¥_¥ 必须为数字~" autocomplete="off" class="layui-input">
                </div>
                <label class="layui-form-label">就诊项目</label>
                <div class="layui-input-inline">
                    <div class="layui-input-inline">
                        <select name="options" lay-verify="required">
                            <?php if(is_array($diseasesList)): foreach($diseasesList as $index=>$vo): ?><option value="<?php echo ($index); ?>"><?php echo ($vo['diseases']); ?></option><?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">性别</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="sex" lay-skin="switch" lay-text="女|男">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="" lay-filter="fromedit">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="layui-container" id="layerAddData" style="display:none;">
    <div class="container">
        <form class="layui-form" lay-filter="formadd">
            <div class="layui-form-item">
                <label class="layui-form-label">回访目标</label>
                <div class="layui-input-inline">
                    <input type="text" name="username" lay-veify="required" placeholder="请输入病人名字或昵称~" autocomplete="off" class="layui-input">
                </div>
                <label class="layui-form-label">回访电话</label>
                <div class="layui-input-inline">
                    <input type="text" name="clientPhone" lay-verify="phone" placeholder="请输入病人联系方式~" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">客服电话</label>
                <div class="layui-input-inline">
                    <input type="text" name="phone" lay-verify="phone" placeholder="请输入客服小姐姐电话~" autocomplete="off" class="layui-input">
                </div>
                <label class="layui-form-label">客服姓名</label>
                <div class="layui-input-inline">
                    <select name="name" lay-verify="required">
                        <?php if(is_array($custservices)): foreach($custservices as $index=>$vo): ?><option value="<?php echo ($index); ?>"><?php echo ($vo['custservice']); ?></option><?php endforeach; endif; ?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">回访状态</label>
                <div class="layui-input-inline">
                    <div class="layui-input-inline">
                        <select name="visitStatus" lay-verify="required">
                            <?php if(is_array($visitstatusValue)): foreach($visitstatusValue as $index=>$vo): ?><option value="<?php echo ($index); ?>"><?php echo ($vo['visitstatus']); ?></option><?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
                <label class="layui-form-label">来院状态</label>
                <div class="layui-input-inline">
                    <div class="layui-input-inline">
                        <select name="status" lay-verify="required">
                            <?php if(is_array($statusValue)): foreach($statusValue as $index=>$vo): ?><option value="<?php echo ($index); ?>"><?php echo ($vo['status']); ?></option><?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">消费金额</label>
                <div class="layui-input-inline">
                    <input type="text" name="money" placeholder="¥_¥ 必须为数字~" autocomplete="off" class="layui-input">
                </div>
                <label class="layui-form-label">就诊项目</label>
                <div class="layui-input-inline">
                    <div class="layui-input-inline">
                        <select name="options" lay-verify="required">
                            <?php if(is_array($diseasesList)): foreach($diseasesList as $index=>$vo): ?><option value="<?php echo ($index); ?>"><?php echo ($vo['diseases']); ?></option><?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">性别</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="sex" lay-skin="switch" lay-text="女|男">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="" lay-filter="fromadd">立即提交</button>
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
        <button class="layui-btn layui-btn-sm" lay-event="add">添加新的信息</button>
    </div>
</script>
<script type="text/html" id="bar">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script>
    layui.use(['table', 'layer', 'laypage', 'form', 'laydate'], () => {
        var table = layui.table;
        var laydate = layui.laydate;
        var layer = layui.layer;
        var form = layui.form;
        var laypage = layui.laypage;
        var $ = layui.jquery;
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
            url: "<?php echo U('Admin/Index/visitCheck');?>",
            height:'full-200',
            page: true,
            cellMinWidth:100,
            even: true,
            limit: 25,
            limits: [25, 50, 75],
            loading: true,
            size: 'sm',
            cols: [[
                {field: 'id', title: 'No .', width: 80, sort: true},
                {field: 'name', title: '姓名', width: 80},
                {field: 'sex', title: '性别', width: 80},
                {field: 'old', title: '年龄', width: 80, sort: true},
                {field: 'phone', title: '电话', width: 120},
                {field: 'qq', title: "QQ", width: 120},
                {field: 'expert', title: '专家号', width: 80},
                {field: 'desc1', title: '咨询内容', width: 80},
                {field: 'oldDate', title: '预约时间', width: 100},
                {field: 'diseases', title: '病患类型', width: 100},
                {field: 'fromAddress', title: '媒体来源', width: 80},
                {field: 'switch', title: '地区', width: 80},
                {field: 'desc2', title: '备注', width: 80},
                {field: 'custService', title: '客服', width: 80},
                {field: 'newDate', title: '回访时间', width: 100},
                {field: 'status', title: '赴约状态', width: 100},
                {field: 'currentTime', title: '添加时间', width: 100},
                {fixed: 'right', title: '操作', width:150, align:'center', toolbar: '#bar'},
            ]],
            id: 'edittable',
            done: function () {

            }
        });
        table.on('tool(edittable)', obj => {
            var data = obj.data;
            var layEvent = obj.event;
            var tr = obj.tr;
            if (layEvent === 'detail') { // check tool data
                layer.open({
                    type: 1,
                    title: '查看信息',
                    area: ['900px', '600px'],
                    content: document.getElementById('layerpopCheck').innerHTML,
                })
            } else if (layEvent === 'del') { // delete tool data
                layer.confirm('【 ' + data.name + ' 】are you soure delete ?', (index) => {
                    var client = new XMLHttpRequest();
                    client.open("GET", "<?php echo U('Admin/Index/visitDel/id/" + parseInt(data.id) + "');?>");
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
                document.getElementById('idValue').innerHTML = data.id;
                layer.open({
                    type: 1,
                    title: '编辑信息',
                    area: ['900px', '600px'],
                    content: document.getElementById('layerpopEdit').innerHTML,
                    // success: () => { console.log("pop page渲染完成"); laydate.render({ elem: '#date' }) }
                });
                setFormValue(data);
                form.on('submit(fromedit)', data => {
                    var id = document.getElementById('idValue').innerHTML;
                    var client = new XMLHttpRequest();
                    client.open('GET', "<?php echo U('Admin/Index/editData/id/" + parseInt(id) + "/data/"+ JSON.stringify(data.field) +"');?>");
                    client.send();
                    client.onreadystatechange = () => {
                        if (client.readyState === 4 && client.status === 200) {
                            if (client.response === 1) {
                                layer.msg('add success', {icon: 6});
                                layer.closeAll('page');
                                tableIns.reload();
                            } else {
                                layer.msg('add failed', {icon: 5});
                            }
                        }
                    }
                    // 由于thinkphp修改了url为兼容模式,导致添加数据过后跳转到登陆页面:(... 未知bug,先这样勉强算修复了.但是现在没有添加成功和失败的弹出框了... Server 环境太老了,只兼容thinkphp3.2.2
                    var parent = window.parent.document.getElementById('iframe');
                    parent.setAttribute("src", "/visit/index.php?s=/Admin/Index/visit");
                    return false;
                });
            }
        });
        table.on('toolbar(edittable)', obj => {
            var checkStatus = table.checkStatus(obj.config.id);
            if (obj.event === 'add') {
                layer.open({
                    type: 1,
                    title: '新增回访信息',
                    area: ['1000px', '600px'],
                    content: document.getElementById('layerAddData').innerHTML,
                });
                form.render();
                form.on('submit(fromadd)', data => {
                    var client = new XMLHttpRequest();
                    client.open('GET', "<?php echo U('Admin/Index/addData/data/"+ JSON.stringify(data.field) +"');?>");
                    client.send();
                    client.onreadystatechange = () => {
                        if (client.readyState === 4 && client.status === 200) {
                            if (client.response === 1) {
                                layer.msg('add success', {icon: 6});
                                layer.closeAll('page');
                                tableIns.reload();
                            } else {
                                layer.msg('add failed', {icon: 5});
                            }
                        }
                    }
                    // 由于thinkphp修改了url为兼容模式,导致添加数据过后跳转到登陆页面:(... 未知bug,先这样勉强算修复了.但是现在没有添加成功和失败的弹出框了... Server 环境太老了,只兼容thinkphp3.2.2
                    var parent = window.parent.document.getElementById('iframe');
                    parent.setAttribute("src", "/visit/index.php?s=/Admin/Index/visit");
                    return false;
                });
            }
        });
        /* search  */
        document.getElementById('searchbtn').onclick = () => {
            var value = document.getElementById('search').value;
            if (value == '') {
                layer.msg('请输入要搜索的信息', {icon: 16});
                tableIns.reload({
                    where: {search: null}
                });
                layer.close(layer.index);
            }
            if (value != '') {
                layer.msg('数据请求中...', {icon: 16});
                tableIns.reload({
                    page: {curr: 1},
                    where: {search: value}
                });
                layer.close(layer.index);
                document.getElementById('search').value = '';
            }
        }
        /* 渲染form表单 */
        setFormValue = data => {
            form.val('formedit', {

            });
            form.render();
        }
    });
</script>
</html>