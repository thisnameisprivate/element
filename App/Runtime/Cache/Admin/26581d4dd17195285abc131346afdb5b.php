<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/element/Public/statics/layui/css/layui.css">
    <style>
        .container{position:relative; width:500px; height: 200px; top:0; left:0; bottom:0; right:0; margin:auto; padding-top: 20px;}
        body{overflow:scroll; margin-top:20px;}
    </style>
    <title>element</title>
</head>
<body>
<table id="container" lay-filter="edittable"></table>
<div class="layui-container" id="layerAddHospital" style="display:none;">
    <div class="container">
        <form class="layui-form">
            <div class="layui-form-item">
                <label class="layui-form-label">医院科室：</label>
                <div class="layui-input-inline">
                    <input type="text" name="hospital" required lay-verify="required" placeholder="请输入新的医院科室" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">数据表名：</label>
                <div class="layui-input-inline">
                    <input type="text" name="tableName" required lay-verify="tableName" placeholder="请输入对应数据表名" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="" lay-filter="formSubmit">立即提交</button>
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
<script type="text/javascript">
    layui.use(['table', 'layer', 'form'], () => {
        var table = layui.table;
        var layer = layui.layer;
        var form = layui.form;
        var tableIns = table.render({
            text: {
                none: "暂无相关数据",
            },
            initSort: {
                field: 'id',
                type: 'asc',
            },
            elem: '#container',
            toolbar: '#toolbaradd',
            url: "<?php echo U('Admin/Index/hospitalsCheck');?>",
            height: 'full-20',
            cellMinWidth: 150,
            size: 'sm',
            cols: [[
                {field: 'id', title: 'No .' , width:80, sort: true},
                {field: 'hospital', title: '医院科室', width: 200},
                {field: 'tableName', title: '对应数据表', width: 200},
                {field: 'addtime', title: '添加时间', width:200},
                {fixed: 'right', title: '操作',  width:150, align:'center', toolbar: '#bar'}
            ]],
            id: 'edittable',
        });
        table.on('tool(edittable)', obj => {
            var data = obj.data;
            var layEvent = obj.event;
            var tr = obj.tr;
            if (layEvent === 'del') {
                layer.confirm('【'+ data.hospital +'】你确定删除吗?', index => {
                    var client = new XMLHttpRequest();
                    client.open('GET', "<?php echo U('Admin/Index/delDepartMent/id/"+ parseInt(data.id) +"');?>");
                    client.send();
                    client.onreadystatechange = () => {
                        if (client.readyState === 4 && client.status === 200) {
                            if (client.response == 1) {
                                layer.msg('delete success', {icon: 6});
                                obj.del();
                                layer.close(index);
                            } else {
                                layer.msg('delete failed', {icon: 5});
                            }
                        }
                    }
                })
            } else if (layEvent === 'edit') {
                layer.open({
                    type: 1,
                    title: '编辑医院科室',
                    area: ['600px', '300px'],
                    content: document.getElementById('layerAddHospital').innerHTML,
                });
                form.on('submit(formSubmit)', data => {
                    var client = new XMLHttpRequest();
                    client.open('GET', "<?php echo U('Admin/Index/hospitalsEdit/id/"+ parseInt(data.id) +"');?>");
                    client.send();
                    client.onreadystatechange = () => {
                        if (client.readyState === 4 && client.status === 200) {
                            if (client.response == 1) {
                                layer.msg('edit success', {icon: 6});
                                obj.del();
                                layer.close(index);
                            } else {
                                layer.msg('edit failed', {icon: 5});
                            }
                        }
                    }
                })
            };
        });
        table.on('toolbar(edittable)', obj => {
            var checkStatus = table.checkStatus(obj.config.id);
            if (obj.event === 'add') {
                layer.open({
                    type: 1,
                    title: '新增医院科室',
                    area: ['600px', '300px'],
                    content: document.getElementById('layerAddHospital').innerHTML,
                });
            };
            form.on('submit(formSubmit)', data => {
                var client = new XMLHttpRequest();
                client.open('GET', "<?php echo U('Admin/Index/addDepartMent/hospital/"+ JSON.stringify(data.field) +"');?>");
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
            form.verify({
                tableName: (value, item) => {
                    if(! /^[a-zA-Z]{5,12}$/.test(value)) {
                        return '字符串必须为5-12位';
                    }
                    if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
                        return '数据表名不能有特殊字符';
                    }
                    if(/^\d+\d+\d$/.test(value)){
                        return '数据表名不能全为数字';
                    }
                    if(/(^\_)|(\__)|(\_+$)/.test(value)){
                        return '数据表名首尾不能出现下划线\'_\'';
                    }
                }
            })
        });
    });
</script>
</html>