<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/element/Public/statics/layui/css/layui.css">
    <title>login Log</title>
</head>
<body>
<table id="container" lay-filter="edittable"></table>
</body>
<script src="/element/Public/statics/layui/layui.js"></script>
<script type="text/javascript">
    layui.use(['element', 'layer', 'table'], () => {
        var element = layui.element;
        var layer = layui.layer;
        var table = layui.table;
        var $ = layui.jquery;
        var tableIns = table.render({
            text: { none: '暂无相关数据' },
            elem: '#container',
            url: "<?php echo U('Admin/Index/loginCheck');?>",
            height: 'full-200',
            page: true,
            even: true,
            cellMinWidth: 50,
            limit: 200,
            limits: [200, 300, 500],
            loading: true,
            size: 'sm',
            cols: [[
                {field: 'username', title: '登录用户', templet: (data) => { return "<span style='color:red;'>" + data.username + "</span>"}},
                {field: 'password', title: '登录密码', templet: (data) => { return "<span style='color:red;'>" + data.password + "</span>"}},
                {field: 'status', title: '登录状态'},
                {field: 'ip', title: '主机IP'},
                {field: 'fromaddress', title: '归属地'},
                {field: 'addtime', title: '登录时间'}
            ]],
            id: 'edittable',
            done: function (res, curr, count) {
                // Request Ok to do.
            }
        })
    })
</script>
</html>