<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/element/Public/statics/layui/css/layui.css">
    <style>
        .container{position:relative; width:500px; height:200px; left:0; top:0; rigth:0; bootom:0; margin:auto; padding-top:20px;}
        body{overflow:scroll; margin-top:20px;}
    </style>
    <title>detail report</title>
</head>
<body>
<!--
     <table class="layui-table" lay-data="{url:'<?php echo U('Admin/Index/detailReportCheck');?>'}">
        <thead>
        <tr>
            <th lay-data="{field:'custservice', align:'center', width:120}">客服</th>
            <th lay-data="{align:'center'}" colspan="3">今日</th>
            <th lay-data="{align:'center'}" colspan="3">昨日</th>
            <th lay-data="{align:'center'}" colspan="3">本月</th>
            <th lay-data="{align:'center'}" colspan="3">上月</th>
        </tr>
        <tr>
            <th lay-data="{width:120, align:'center', field:'arrivalTotal'}"></th>
            <th lay-data="{field:'arrival', width:120}">总数</th>
            <th lay-data="{field:'arrivalOut', width:120}">已到</th>
            <th lay-data="{field:'yestserTotal', width:120}">未到</th>
            <th lay-data="{field:'yesterArrival', width:120}">总数</th>
            <th lay-data="{field:'yesterArrivalOut', width:120}">已到</th>
            <th lay-data="{field:'thisTotal', width:120}">未到</th>
            <th lay-data="{field:'thisArrival', width:120}">总数</th>
            <th lay-data="{field:'thisArrivalOut', width:120}">已到</th>
            <th lay-data="{field:'lastTotal', width:120}">未到</th>
            <th lay-data="{field:'lastArrival', width:120}">总数</th>
            <th lay-data="{field:'lastArrivalOut', width:120}">已到</th>
            <th lay-data="{width:120}">未到</th>
        </tr>
    </thead>
    </table>
 -->
<table id="container" lay-filter="edittable"></table>
</body>
<script src="/element/Public/statics/layui/layui.js"></script>
<script type="text/javascript">
    layui.use(['table', 'layer', 'form'], () => {
        var table = layui.table;
        var layer = layui.layer;
        var form = layui.form;
        var tableIns = table.render({
            text: { none: '暂无相关数据' },
            elem: '#container',
            url: "<?php echo U('Admin/Index/detailReportCheck');?>",
            height: 'full-200',
            size: 'sm',
            cols: [[
                {field: 'custservice', title: '客服', width: 80},
                {field: 'arrivalTotal', title: '总数', width: 100},
                {field: 'arrival', title: '已到', width: 100},
                {field: 'arrivalOut', title: '未到', width: 100},
                {field: 'yestserTotal', title: '总数', width: 100},
                {field: 'yesterArrival', title: '已到', width: 100},
                {field: 'yesterArrivalOut', title: '未到', width: 100},
                {field: 'thisTotal', title: '总数', width: 100},
                {field: 'thisArrival', title: '已到', width: 100},
                {field: 'thisArrivalOut', title: '未到', width: 100},
                {field: 'lastTotal', title: '总数', width: 100},
                {field: 'lastArrival', title: '已到', width: 100},
                {field: 'lastArrivalOut', title: '未到', width: 100}
            ]],
            id: 'edittable'
        });
    })
</script>
</html>