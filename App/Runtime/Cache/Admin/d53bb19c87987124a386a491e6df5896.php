<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/element/Public/statics/layui/css/layui.css">
    <title>personal</title>
</head>
<body>
<table id="container" lay-filter="edittable"></table>
</body>
<script src="/element/Public/statics/layui/layui.js"></script>
<script type="text/javascript">
    layui.use(['table', 'form'], () => {
        /*
        var table = layer.table;
        var form = layer.form;
        var $ = layer.jquery;
        var userAcc = JSON.parse(localStorage.getItem('userAcc'));
        var tableIns = table.render({
            text: { none: '暂无相关数据' },
            initSort: {
                field: 'id',
                type: 'dsec',
            },
            elem: '#container',

        })


           */
        layer.msg("开发中", {icon:5});
    })
</script>
</html>